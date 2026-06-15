<?php

/**
 * Manutenzione - Riallineamento header FITS alla configurazione attuale.
 *
 * Freeture scrive nell'header di ogni file .fit una serie di keyword (TELESCOP, OBSERVER,
 * INSTRUME, SITELAT, ...) lette da configuration.cfg al momento dell'acquisizione. Se la
 * stazione viene (ri)configurata, i file storici mantengono nell'header i vecchi valori.
 *
 * Questa logica:
 *  - Legge i valori desiderati dalla sezione FITS di configuration.cfg.
 *  - Scansiona i file .fit sotto /freeture/<srcCode>/ (ricorsivo) e produce un'anteprima
 *    AGGREGATA per keyword (valore attuale -> valore nuovo, numero file interessati).
 *  - Applica la modifica riscrivendo IN-PLACE la singola card da 80 byte di ogni keyword
 *    GIA' PRESENTE nell'header (dimensione file invariata, dati immagine intatti).
 *
 * Vincoli/decisioni:
 *  - Solo modifica del VALORE di keyword esistenti: non vengono aggiunte keyword nuove ne'
 *    allungato l'header (eviterebbe il rewrite completo del file).
 *  - STATION_NAME (>8 char, non e' una keyword FITS) e COMMENT (ambigua) sono escluse.
 *  - APERTURE (chiave cfg) viene mappata sulla keyword FITS APERTUR.
 *
 * @author: N3 S.r.l.
 */
class FitsHeaderApiLogic {

	// Dimensione di un blocco header FITS e di una card.
	const FITS_BLOCK = 2880;
	const FITS_CARD  = 80;

	// Numero massimo di blocchi header letti per file (20 * 2880 = 57600 byte): oltre si
	// considera l'header non gestibile e il file viene segnalato come errore.
	const MAX_HEADER_BLOCKS = 20;

	// Tetto di file analizzati in ANTEPRIMA (i valori sono uniformi tra i file, basta un
	// campione). L'APPLICAZIONE invece processa tutti i file senza tetto.
	const PREVIEW_FILE_CAP = 4000;

	// Mappa: chiave in configuration.cfg => keyword FITS scritta nell'header.
	// Identita' tranne APERTURE -> APERTUR. STATION_NAME e COMMENT volutamente escluse.
	private static $KEY_MAP = array(
		'TELESCOP' => 'TELESCOP',
		'OBSERVER' => 'OBSERVER',
		'INSTRUME' => 'INSTRUME',
		'CAMERA'   => 'CAMERA',
		'FOCAL'    => 'FOCAL',
		'APERTURE' => 'APERTUR',
		'SITELONG' => 'SITELONG',
		'SITELAT'  => 'SITELAT',
		'SITEELEV' => 'SITEELEV',
		'FILTER'   => 'FILTER',
		'K1'       => 'K1',
		'K2'       => 'K2',
		'CD1_1'    => 'CD1_1',
		'CD1_2'    => 'CD1_2',
		'CD2_1'    => 'CD2_1',
		'CD2_2'    => 'CD2_2',
		'XPIXEL'   => 'XPIXEL',
		'YPIXEL'   => 'YPIXEL',
	);

	/**
	 * GET /manutenzione/fits/scan?srcCode=...
	 * Ritorna { srcCode, sourceRoot, rootExists, config, totalFiles, scannedFiles,
	 *           capped, summary: [...], errors: [...] } senza modificare nulla.
	 */
	public static function Scan($request = null) {
		try {
			$Person = CoreLogic::VerifyPerson();

			$srcCode = self::readSrcCode($request);
			$fitsCfg = self::getFitsConfig();

			$payload = self::buildScanPayload($srcCode, $fitsCfg);
		} catch (ApiException $a) {
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse(true, $payload);
	}

	/**
	 * POST /manutenzione/fits/run  { token, srcCode }
	 * Applica i valori della config a tutti i file .fit della sorgente e ritorna il riepilogo.
	 */
	public static function Run($request) {
		try {
			$Person = CoreLogic::VerifyPerson();
			CoreLogic::CheckCSRF($request->get("token"));

			$srcCode = self::readSrcCode($request);
			if (!self::isValidIdentifier($srcCode)) {
				throw new ApiException("Codice stazione sorgente non valido (consentiti solo [A-Za-z0-9._-]).");
			}

			$fitsCfg = self::getFitsConfig();
			if (empty($fitsCfg)) {
				throw new ApiException("Nessuna keyword FITS valorizzata in configuration.cfg: niente da applicare.");
			}

			$root = self::sourceRoot($srcCode);
			if (!is_dir($root)) {
				throw new ApiException("Cartella sorgente inesistente: $root");
			}

			$result = self::applyToTree($root, $fitsCfg);
		} catch (ApiException $a) {
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse(true, $result);
	}

	//-------------------------------------------------------------------------
	// Config
	//-------------------------------------------------------------------------

	/**
	 * Legge dalla sezione FITS di configuration.cfg i valori desiderati.
	 * Ritorna array keywordFITS => valore (string), solo per le chiavi presenti nel file.
	 */
	private static function getFitsConfig() {
		$cfg = array();
		$freetureConf = _FREETURE_;
		if (!file_exists($freetureConf) || !is_file($freetureConf)) {
			return $cfg;
		}
		foreach (file($freetureConf) as $line) {
			if (!isset($line) || $line === '' || $line[0] === '#' || $line[0] === "\n" || $line[0] === "\t") {
				continue;
			}
			if ((strlen($line) - 1) === substr_count($line, " ")) {
				continue;
			}
			if (strpos($line, '=') === false) {
				continue;
			}
			$key = CoreLogic::getKey($line);
			if (isset(self::$KEY_MAP[$key])) {
				$cfg[self::$KEY_MAP[$key]] = CoreLogic::getValue($line);
			}
		}
		return $cfg;
	}

	//-------------------------------------------------------------------------
	// Scan / anteprima
	//-------------------------------------------------------------------------

	private static function buildScanPayload($srcCode, $fitsCfg) {
		$root = self::sourceRoot($srcCode);

		$payload = array(
			'srcCode'      => $srcCode,
			'sourceRoot'   => $root,
			'rootExists'   => is_dir($root),
			'config'       => $fitsCfg,
			'totalFiles'   => 0,
			'scannedFiles' => 0,
			'capped'       => false,
			'summary'      => array(),
			'errors'       => array(),
		);

		if (!$payload['rootExists'] || empty($fitsCfg)) {
			return $payload;
		}

		// Accumulatori per keyword.
		$agg = array(); // kw => ['found'=>int,'change'=>int,'sampleOld'=>string,'distinct'=>[val=>1]]
		foreach ($fitsCfg as $kw => $newVal) {
			$agg[$kw] = array('found' => 0, 'change' => 0, 'sampleOld' => null, 'distinct' => array());
		}

		$files   = self::listFitFiles($root);
		$payload['totalFiles'] = count($files);
		$scanned = 0;
		$errors  = array();

		foreach ($files as $file) {
			if ($scanned >= self::PREVIEW_FILE_CAP) {
				$payload['capped'] = true;
				break;
			}
			$per = self::scanFileKeywords($file, $fitsCfg, $err);
			if ($per === null) {
				if ($err !== '' && count($errors) < 20) {
					$errors[] = basename($file) . ": " . $err;
				}
				continue;
			}
			$scanned++;
			foreach ($per as $kw => $info) {
				$agg[$kw]['found']++;
				$old = $info['old'];
				$agg[$kw]['distinct'][$old] = true;
				if ($agg[$kw]['sampleOld'] === null) {
					$agg[$kw]['sampleOld'] = $old;
				}
				if ($info['willChange']) {
					$agg[$kw]['change']++;
				}
			}
		}

		$summary = array();
		foreach ($fitsCfg as $kw => $newVal) {
			$a = $agg[$kw];
			$summary[] = array(
				'keyword'         => $kw,
				'newValue'        => $newVal,
				'filesWithKey'    => $a['found'],
				'filesToChange'   => $a['change'],
				'sampleOldValue'  => $a['sampleOld'],
				'distinctOldVals' => count($a['distinct']),
			);
		}

		$payload['scannedFiles'] = $scanned;
		$payload['summary']      = $summary;
		$payload['errors']       = $errors;
		return $payload;
	}

	/**
	 * Per un file ritorna array kw => ['old'=>string,'willChange'=>bool] per le sole
	 * keyword (tra quelle in $fitsCfg) effettivamente presenti nell'header.
	 * Ritorna null + $err se il file non e' leggibile / header non gestibile.
	 */
	private static function scanFileKeywords($path, $fitsCfg, &$err) {
		$err = '';
		$cards = self::readHeaderCards($path, $err);
		if ($cards === null) {
			return null;
		}
		$out = array();
		foreach ($cards as $card) {
			$kw = rtrim(substr($card, 0, 8));
			if (!isset($fitsCfg[$kw]) || isset($out[$kw])) {
				continue;
			}
			if (substr($card, 8, 2) !== '= ') {
				continue; // non e' una value-card (es. COMMENT/HISTORY)
			}
			$parsed   = self::parseValueField($card);
			$oldVal   = $parsed['value'];
			$newRaw   = $fitsCfg[$kw];
			$newCard  = self::buildCard($kw, $newRaw, $parsed['isString'], $parsed['comment']);
			$out[$kw] = array(
				'old'        => $oldVal,
				'willChange' => ($newCard !== null && $newCard !== $card),
			);
		}
		return $out;
	}

	//-------------------------------------------------------------------------
	// Apply
	//-------------------------------------------------------------------------

	private static function applyToTree($root, $fitsCfg) {
		$files = self::listFitFiles($root);
		$result = array(
			'totalFiles'    => count($files),
			'filesChanged'  => 0,
			'filesSkipped'  => 0,
			'filesError'    => 0,
			'cardsWritten'  => 0,
			'perKeyword'    => array(), // kw => count
			'errors'        => array(),
		);
		foreach ($fitsCfg as $kw => $v) {
			$result['perKeyword'][$kw] = 0;
		}

		foreach ($files as $file) {
			$err = '';
			$written = self::applyFile($file, $fitsCfg, $result['perKeyword'], $err);
			if ($written === null) {
				$result['filesError']++;
				if (count($result['errors']) < 50) {
					$result['errors'][] = basename($file) . ": " . $err;
				}
				continue;
			}
			if ($written > 0) {
				$result['filesChanged']++;
				$result['cardsWritten'] += $written;
			} else {
				$result['filesSkipped']++;
			}
		}
		return $result;
	}

	/**
	 * Applica le modifiche a un singolo file. Ritorna il numero di card riscritte,
	 * 0 se nulla da cambiare, null + $err in caso di errore.
	 */
	private static function applyFile($path, $fitsCfg, &$perKeyword, &$err) {
		$err = '';
		$cards = self::readHeaderCards($path, $err);
		if ($cards === null) {
			return null;
		}

		// Calcola le card da riscrivere (offset = indice * 80, header a offset 0).
		$writes = array(); // [offset => newCard]
		$seen   = array();
		foreach ($cards as $idx => $card) {
			$kw = rtrim(substr($card, 0, 8));
			if (!isset($fitsCfg[$kw]) || isset($seen[$kw])) {
				continue;
			}
			if (substr($card, 8, 2) !== '= ') {
				continue;
			}
			$seen[$kw] = true;
			$parsed  = self::parseValueField($card);
			$newCard = self::buildCard($kw, $fitsCfg[$kw], $parsed['isString'], $parsed['comment']);
			if ($newCard === null) {
				if ($err === '') {
					$err = "valore troppo lungo per la keyword $kw";
				}
				continue;
			}
			if ($newCard !== $card) {
				$writes[$idx * self::FITS_CARD] = array($kw, $newCard);
			}
		}

		if (empty($writes)) {
			return 0;
		}

		$fh = @fopen($path, 'r+b');
		if ($fh === false) {
			$err = "impossibile aprire in scrittura";
			return null;
		}
		$count = 0;
		foreach ($writes as $offset => $pair) {
			$kw      = $pair[0];
			$newCard = $pair[1];
			if (@fseek($fh, $offset, SEEK_SET) !== 0) {
				continue;
			}
			$n = @fwrite($fh, $newCard, self::FITS_CARD);
			if ($n === self::FITS_CARD) {
				$count++;
				$perKeyword[$kw]++;
			}
		}
		@fclose($fh);
		return $count;
	}

	//-------------------------------------------------------------------------
	// FITS header parsing / card building
	//-------------------------------------------------------------------------

	/**
	 * Legge i blocchi header (a partire da offset 0) finche' incontra la card END o il
	 * tetto MAX_HEADER_BLOCKS. Ritorna l'array delle card (stringhe da 80 char) fino a END
	 * escluso, oppure null + $err.
	 */
	private static function readHeaderCards($path, &$err) {
		$err = '';
		$fh = @fopen($path, 'rb');
		if ($fh === false) {
			$err = "impossibile aprire";
			return null;
		}
		$header   = '';
		$endIndex = -1;
		for ($b = 0; $b < self::MAX_HEADER_BLOCKS; $b++) {
			$blk = @fread($fh, self::FITS_BLOCK);
			if ($blk === false || $blk === '') {
				break;
			}
			$header .= $blk;
			$nCards = intdiv(strlen($header), self::FITS_CARD);
			for ($c = 0; $c < $nCards; $c++) {
				$kw = rtrim(substr($header, $c * self::FITS_CARD, 8));
				if ($kw === 'END') {
					$endIndex = $c;
					break;
				}
			}
			if ($endIndex >= 0) {
				break;
			}
		}
		@fclose($fh);

		if ($endIndex < 0) {
			$err = "card END non trovata (header > " . (self::MAX_HEADER_BLOCKS * self::FITS_BLOCK) . " byte o file non FITS)";
			return null;
		}
		// Sanity check minimale: primo keyword deve essere SIMPLE.
		if (rtrim(substr($header, 0, 6)) !== 'SIMPLE') {
			$err = "header non valido (manca SIMPLE)";
			return null;
		}

		$cards = array();
		for ($c = 0; $c < $endIndex; $c++) {
			$cards[$c] = substr($header, $c * self::FITS_CARD, self::FITS_CARD);
		}
		return $cards;
	}

	/**
	 * Analizza il value-field (col 11-80) di una card. Ritorna
	 * ['value'=>string, 'isString'=>bool, 'comment'=>string].
	 */
	private static function parseValueField($card) {
		$vf   = substr($card, 10); // colonne 11-80
		$trim = ltrim($vf);
		$isString = (isset($trim[0]) && $trim[0] === "'");

		if ($isString) {
			$open = strpos($vf, "'");
			$len  = strlen($vf);
			$j    = $open + 1;
			$value = '';
			while ($j < $len) {
				if ($vf[$j] === "'") {
					if ($j + 1 < $len && $vf[$j + 1] === "'") {
						$value .= "'";
						$j += 2;
						continue;
					}
					break; // quote di chiusura
				}
				$value .= $vf[$j];
				$j++;
			}
			$value = rtrim($value);
			$comment = '';
			$after = substr($vf, $j + 1);
			$slash = strpos($after, '/');
			if ($slash !== false) {
				$comment = trim(substr($after, $slash + 1));
			}
			return array('value' => $value, 'isString' => true, 'comment' => $comment);
		}

		// Valore non-stringa (numerico/logico): token prima dell'eventuale commento.
		$comment = '';
		$slash = strpos($vf, '/');
		if ($slash !== false) {
			$valuePart = substr($vf, 0, $slash);
			$comment   = trim(substr($vf, $slash + 1));
		} else {
			$valuePart = $vf;
		}
		return array('value' => trim($valuePart), 'isString' => false, 'comment' => $comment);
	}

	/**
	 * Costruisce una card FITS da 80 char per $keyword con $newRaw, preservando tipo
	 * (string vs numerico, dedotto dalla card esistente) ed eventuale commento.
	 * Ritorna null se il risultato non sta in 80 char.
	 */
	private static function buildCard($keyword, $newRaw, $isString, $comment) {
		$kw = str_pad(substr(strtoupper($keyword), 0, 8), 8, ' ', STR_PAD_RIGHT);

		if ($isString) {
			$v = str_replace("'", "''", $newRaw);
			if (strlen($v) < 8) {
				$v = str_pad($v, 8, ' ', STR_PAD_RIGHT); // minimo 8 char tra apici (FITS)
			}
			$value = "'" . $v . "'";
		} else {
			$value = trim($newRaw);
			if ($value === '') {
				return null;
			}
		}

		$card = $kw . '= ' . $value;
		if (strlen($card) > self::FITS_CARD) {
			return null;
		}

		if ($comment !== '') {
			$candidate = $card . ' / ' . $comment;
			if (strlen($candidate) <= self::FITS_CARD) {
				$card = $candidate;
			} else {
				$room = self::FITS_CARD - strlen($card) - 3;
				if ($room > 0) {
					$card = $card . ' / ' . substr($comment, 0, $room);
				}
			}
		}

		return str_pad($card, self::FITS_CARD, ' ', STR_PAD_RIGHT);
	}

	//-------------------------------------------------------------------------
	// Filesystem helpers
	//-------------------------------------------------------------------------

	private static function sourceRoot($srcCode) {
		return rtrim(_FREETURE_DATA_, "/") . "/" . $srcCode;
	}

	/**
	 * Ritorna tutti i file .fit/.fits sotto $root (ricorsivo).
	 */
	private static function listFitFiles($root) {
		$files = array();
		if (!is_dir($root)) {
			return $files;
		}
		$it = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
			RecursiveIteratorIterator::LEAVES_ONLY
		);
		foreach ($it as $fileInfo) {
			if (!$fileInfo->isFile()) {
				continue;
			}
			$ext = strtolower($fileInfo->getExtension());
			if ($ext === 'fit' || $ext === 'fits') {
				$files[] = $fileInfo->getPathname();
			}
		}
		return $files;
	}

	private static function readSrcCode($request) {
		$srcCode = _DEFAULT_STATION_CODE_;
		if ($request !== null) {
			$c = $request->get('srcCode');
			if (is_string($c) && trim($c) !== '') {
				$srcCode = trim($c);
			}
		}
		return $srcCode;
	}

	private static function isValidIdentifier($s) {
		return is_string($s) && $s !== '' && preg_match('/^[A-Za-z0-9._-]+$/', $s) === 1;
	}
}
