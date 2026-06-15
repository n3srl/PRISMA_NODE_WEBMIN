<?php

/**
 * Manutenzione - Migrazione folder/file da una stazione SORGENTE a quella di DESTINAZIONE.
 *
 * Caso storico (default): quando un nodo PRISMA esce dalla fabbrica senza configurazione,
 * freeture scrive in
 *   /freeture/DEFAULT/DEFAULT_YYYYMMDD/{captures,stacks,events}/DEFAULT_*.fit
 * Una volta arrivato sul campo e configurato (STATION_CODE / STATION_NAME impostati in
 * configuration.cfg), i nuovi dati finiscono in /freeture/<STATION_CODE>/<STATION_CODE>_YYYYMMDD/...
 * ma i dati storici rimangono orfani sotto DEFAULT.
 *
 * Caso generale: lo stesso problema si presenta quando un nodo e' stato configurato per
 * errore con un codice/nome stazione e poi corretto. I dati storici restano sotto la
 * vecchia cartella stazione (la SORGENTE) e vanno migrati verso quella attuale (la
 * DESTINAZIONE, sempre letta da configuration.cfg).
 *
 * SORGENTE = parametrizzabile (srcCode / srcName). Default: DEFAULT / DEFAULT.
 * DESTINAZIONE = sempre STATION_CODE / STATION_NAME letti da configuration.cfg.
 *
 * Convenzione prefissi freeture (context-dependent), risolta sul nome cartella/file:
 *   - root folder       -> STATION_CODE
 *   - day folder        -> STATION_CODE  (<CODE>_YYYYMMDD)
 *   - captures/*.fit    -> STATION_CODE
 *   - stacks/*.fit      -> STATION_NAME
 *   - events/<evt>/     -> STATION_NAME
 *   - events/<evt>/*    -> STATION_NAME
 *
 * Questa logica:
 *  - Scansiona ricorsivamente /freeture/<srcCode>/ producendo un piano di rinomino
 *  - Applica il rinomino in ordine depth-first reverse (file -> event-folder -> day-folder)
 *    cosi i path parent restano validi finche' il loro turno non arriva.
 *  - Step finale = merge ricorsivo (NON rename) del contenuto di /freeture/<srcCode>/ dentro
 *    /freeture/<dstCode>/, perche' la destinazione esiste gia' nel 99% dei casi e
 *    rename() su destinazione esistente fallisce. Lo step di merge viene aggiunto solo se
 *    srcCode != dstCode (se coincidono i dati sono gia' nella root corretta).
 *
 * @author: N3 S.r.l.
 */
class ManutenzioneApiLogic {

	// Token usato sia come STATION_CODE che STATION_NAME quando il nodo non e' configurato.
	// E' anche il valore di default della SORGENTE.
	const DEFAULT_TOKEN = 'DEFAULT';

	// Tipi item nel piano di migrazione.
	const TYPE_FILE       = 'file';
	const TYPE_EVENT_DIR  = 'event_dir';
	const TYPE_DAY_DIR    = 'day_dir';
	// L'ultimo step e' un merge ricorsivo del contenuto di /freeture/<srcCode>/ dentro
	// /freeture/<dstCode>/ (NON un rename atomico), perche' la dir di destinazione
	// esiste quasi sempre gia' (freeture la crea appena configurato).
	const TYPE_ROOT_MERGE = 'root_merge';

	// Stati esecuzione.
	const STATUS_RENAMED = 'renamed';
	const STATUS_MERGED  = 'merged';
	const STATUS_SKIPPED = 'skipped';
	const STATUS_ERROR   = 'error';

	/**
	 * GET /manutenzione/migration/sources
	 * Ritorna l'elenco delle cartelle stazione presenti sotto _FREETURE_DATA_ (candidate
	 * sorgenti) + la configurazione di destinazione corrente.
	 * Ritorna { sources: [...], stationCode, stationName, configIsValid, defaultToken }.
	 */
	public static function ListSources() {
		try {
			$Person = CoreLogic::VerifyPerson();

			$dstCode = CoreLogic::GetStationCode();
			$dstName = self::getStationName();

			$base    = rtrim(_FREETURE_DATA_, "/");
			$sources = self::listDirs($base);
			sort($sources, SORT_STRING | SORT_FLAG_CASE);

			$payload = array(
				'sources'       => $sources,
				'stationCode'   => $dstCode,
				'stationName'   => $dstName,
				'configIsValid' => self::isConfigValid($dstCode, $dstName),
				'defaultToken'  => self::DEFAULT_TOKEN,
			);
		} catch (ApiException $a) {
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse(true, $payload);
	}

	/**
	 * GET /manutenzione/migration/scan?srcCode=...&srcName=...
	 * Ritorna { srcCode, srcName, stationCode, stationName, sourceRoot, items: [...] }
	 * con il piano di rinomino senza applicarlo. Se i parametri sorgente non vengono
	 * passati, default DEFAULT / DEFAULT (compatibilita' col comportamento storico).
	 */
	public static function ScanDefaults($request = null) {
		try {
			$Person = CoreLogic::VerifyPerson();

			list($srcCode, $srcName) = self::readSource($request);
			$dstCode = CoreLogic::GetStationCode();
			$dstName = self::getStationName();

			$payload = self::buildScanPayload($srcCode, $srcName, $dstCode, $dstName);
		} catch (ApiException $a) {
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse(true, $payload);
	}

	/**
	 * POST /manutenzione/migration/run  { token, srcCode, srcName }
	 * Esegue la migrazione e ritorna l'elenco dei risultati per ogni item.
	 */
	public static function RunMigration($request) {
		try {
			$Person = CoreLogic::VerifyPerson();
			CoreLogic::CheckCSRF($request->get("token"));

			list($srcCode, $srcName) = self::readSource($request);
			$dstCode = CoreLogic::GetStationCode();
			$dstName = self::getStationName();

			if (!self::isConfigValid($dstCode, $dstName)) {
				throw new ApiException(
					"La configurazione freeture di destinazione e' ancora DEFAULT (STATION_CODE=$dstCode, STATION_NAME=$dstName). " .
					"Configurare prima la stazione e ricaricare la pagina."
				);
			}

			if (!self::isValidIdentifier($srcCode) || !self::isValidIdentifier($srcName)) {
				throw new ApiException("Codice/nome stazione SORGENTE non validi (consentiti solo [A-Za-z0-9._-]).");
			}
			if (!self::isValidIdentifier($dstCode) || !self::isValidIdentifier($dstName)) {
				throw new ApiException("STATION_CODE/STATION_NAME di destinazione contengono caratteri non ammessi (consentiti solo [A-Za-z0-9._-]).");
			}

			if ($srcCode === $dstCode && $srcName === $dstName) {
				throw new ApiException("Sorgente e destinazione coincidono (STATION_CODE=$srcCode, STATION_NAME=$srcName): niente da migrare.");
			}

			$plan = self::buildPlan($srcCode, $srcName, $dstCode, $dstName);
			self::applyParentRenames($plan);
			$results = self::executePlan($plan);
		} catch (ApiException $a) {
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse(true, $results);
	}

	//-------------------------------------------------------------------------
	// Internals
	//-------------------------------------------------------------------------

	/**
	 * Legge srcCode/srcName dalla request (query string per GET, body per POST).
	 * Default DEFAULT / DEFAULT. $request e' un Symfony ParameterBag (->get()).
	 */
	private static function readSource($request) {
		$srcCode = self::DEFAULT_TOKEN;
		$srcName = self::DEFAULT_TOKEN;
		if ($request !== null) {
			$c = $request->get('srcCode');
			$n = $request->get('srcName');
			if (is_string($c) && trim($c) !== '') {
				$srcCode = trim($c);
			}
			if (is_string($n) && trim($n) !== '') {
				$srcName = trim($n);
			}
		}
		return array($srcCode, $srcName);
	}

	private static function isConfigValid($code, $name) {
		return $code !== self::DEFAULT_TOKEN && $name !== self::DEFAULT_TOKEN
			&& $code !== '' && $name !== '';
	}

	private static function buildScanPayload($srcCode, $srcName, $dstCode, $dstName) {
		$sourceRoot    = rtrim(_FREETURE_DATA_, "/") . "/" . $srcCode;
		$configIsValid = self::isConfigValid($dstCode, $dstName);

		$payload = array(
			'srcCode'        => $srcCode,
			'srcName'        => $srcName,
			'stationCode'    => $dstCode,
			'stationName'    => $dstName,
			'sourceRoot'     => $sourceRoot,
			'rootExists'     => is_dir($sourceRoot),
			'configIsValid'  => $configIsValid,
			'items'          => array(),
		);

		if (!$payload['rootExists']) {
			return $payload;
		}

		// Se la config di destinazione non e' ancora valida costruiamo comunque la preview
		// usando placeholder testuali, cosi l'utente vede l'elenco di cosa verra' rinominato
		// (in sola lettura).
		$planCode = $configIsValid ? $dstCode : '<STATION_CODE>';
		$planName = $configIsValid ? $dstName : '<STATION_NAME>';

		$plan = self::buildPlan($srcCode, $srcName, $planCode, $planName);
		self::applyParentRenames($plan); // aggiunge 'final_path' a ogni item

		$preview = array();
		foreach ($plan as $idx => $item) {
			$preview[] = array_merge($item, array(
				'id'       => $idx,
				// Il check conflitto ha senso solo con path reali.
				'conflict' => $configIsValid ? file_exists($item['new_path']) : false,
			));
		}
		$payload['items'] = $preview;
		return $payload;
	}

	/**
	 * Per ogni item del piano calcola il "final_path", cioe' il path finale dove
	 * l'elemento si trovera' DOPO che TUTTI gli step di rinomino sono stati eseguiti
	 * (compresi quelli sulle cartelle parent). Utile per la preview UI.
	 *
	 * Il piano viene eseguito depth-first reverse (file -> event-dir -> day-dir -> root):
	 * al momento del rename di un singolo file il parent path e' ancora quello vecchio,
	 * quindi 'new_path' contiene il path sorgente sui parent. 'final_path' applica anche
	 * i rinomini dei parent presenti piu' avanti nel piano.
	 */
	private static function applyParentRenames(&$plan) {
		$dirRenames = array();
		foreach ($plan as $item) {
			if ($item['type'] === self::TYPE_EVENT_DIR
				|| $item['type'] === self::TYPE_DAY_DIR
				|| $item['type'] === self::TYPE_ROOT_MERGE) {
				$dirRenames[] = array($item['old_path'], $item['new_path']);
			}
		}
		// Ordino dal path piu' lungo al piu' corto: cosi' il match parente avviene
		// sempre sulla cartella piu' specifica per prima.
		usort($dirRenames, function ($a, $b) {
			return strlen($b[0]) - strlen($a[0]);
		});

		foreach ($plan as &$item) {
			$final = $item['new_path'];
			foreach ($dirRenames as $pair) {
				$oldDir = $pair[0];
				$newDir = $pair[1];
				if ($final === $oldDir) {
					$final = $newDir;
				} elseif (strpos($final, $oldDir . '/') === 0) {
					$final = $newDir . substr($final, strlen($oldDir));
				}
			}
			$item['final_path'] = $final;
		}
		unset($item);
	}

	/**
	 * Costruisce il piano di rinomino in ordine di esecuzione (depth-first reverse).
	 * Il "new_path" di ciascun item utilizza il path PARENT VECCHIO + nome nuovo:
	 * cosi quando viene eseguito il rinomino in ordine, ogni step trova path validi.
	 *
	 * Regole di prefisso (context-dependent), sorgente -> destinazione:
	 *   - root folder       -> srcCode -> dstCode
	 *   - day folder        -> srcCode -> dstCode
	 *   - captures/*.fit    -> srcCode -> dstCode
	 *   - stacks/*.fit      -> srcName -> dstName
	 *   - events/<evt>/     -> srcName -> dstName (nome cartella evento)
	 *   - events/<evt>/*    -> srcName -> dstName (file dentro la cartella evento)
	 */
	private static function buildPlan($srcCode, $srcName, $dstCode, $dstName) {
		$plan = array();
		$root = rtrim(_FREETURE_DATA_, "/") . "/" . $srcCode;
		if (!is_dir($root)) {
			return $plan;
		}

		// 1) Day folders sotto la root sorgente.
		$dayDirs = self::listDirs($root);
		foreach ($dayDirs as $dayDir) {
			$dayPath = $root . "/" . $dayDir;

			// 2) Sub-folder per tipo dati (captures/stacks/events/...).
			foreach (self::listDirs($dayPath) as $subDir) {
				$subPath = $dayPath . "/" . $subDir;

				if ($subDir === 'events') {
					// Cartelle evento + file interni: prefisso STATION_NAME.
					foreach (self::listDirs($subPath) as $eventDir) {
						$eventPath = $subPath . "/" . $eventDir;
						self::collectFiles($eventPath, $srcName, $dstName, $plan);
						$newEventName = self::replacePrefix($eventDir, $srcName, $dstName);
						if ($newEventName !== null && $newEventName !== $eventDir) {
							$plan[] = array(
								'type'     => self::TYPE_EVENT_DIR,
								'old_path' => $eventPath,
								'new_path' => $subPath . "/" . $newEventName,
							);
						}
					}
				} elseif ($subDir === 'captures') {
					// captures/*.fit -> prefisso STATION_CODE.
					self::collectFiles($subPath, $srcCode, $dstCode, $plan);
				} elseif ($subDir === 'stacks') {
					// stacks/*.fit -> prefisso STATION_NAME.
					self::collectFiles($subPath, $srcName, $dstName, $plan);
				} else {
					// Sub-folder generiche (logs/, sessions/, ...): fallback su STATION_NAME.
					self::collectFiles($subPath, $srcName, $dstName, $plan);
				}
			}

			// File sorgente direttamente nella day folder (raro): fallback su STATION_CODE
			// per coerenza con il nome della day folder a cui appartengono.
			self::collectFiles($dayPath, $srcCode, $dstCode, $plan);

			// 3) Day folder <srcCode>_YYYYMMDD -> <dstCode>_YYYYMMDD.
			$newDayName = self::replacePrefix($dayDir, $srcCode, $dstCode);
			if ($newDayName !== null && $newDayName !== $dayDir) {
				$plan[] = array(
					'type'     => self::TYPE_DAY_DIR,
					'old_path' => $dayPath,
					'new_path' => $root . "/" . $newDayName,
				);
			}
		}

		// 4) File sorgente direttamente sotto /freeture/<srcCode>/ (raro): STATION_CODE.
		self::collectFiles($root, $srcCode, $dstCode, $plan);

		// 5) Root: merge ricorsivo /freeture/<srcCode>/* -> /freeture/<dstCode>/* e rmdir sorgente.
		//    Solo se le root differiscono: se srcCode == dstCode i dati sono gia' nella root
		//    corretta e vanno solo rinominati i prefissi interni (stacks/events).
		if ($srcCode !== $dstCode) {
			$plan[] = array(
				'type'     => self::TYPE_ROOT_MERGE,
				'old_path' => $root,
				'new_path' => rtrim(_FREETURE_DATA_, "/") . "/" . $dstCode,
			);
		}

		return $plan;
	}

	private static function collectFiles($dirPath, $oldPrefix, $newPrefix, &$plan) {
		if (!is_dir($dirPath)) {
			return;
		}
		$entries = @scandir($dirPath);
		if ($entries === false) {
			return;
		}
		foreach ($entries as $entry) {
			if ($entry === '.' || $entry === '..') {
				continue;
			}
			$full = $dirPath . "/" . $entry;
			if (!is_file($full)) {
				continue;
			}
			$newName = self::replacePrefix($entry, $oldPrefix, $newPrefix);
			if ($newName === null || $newName === $entry) {
				continue;
			}
			$plan[] = array(
				'type'     => self::TYPE_FILE,
				'old_path' => $full,
				'new_path' => $dirPath . "/" . $newName,
			);
		}
	}

	/**
	 * Restituisce il nome rinominato sostituendo il prefisso $oldPrefix con $newPrefix.
	 * Match esatto del prefisso: "<old>_qualcosa", "<old>.qualcosa", "<old>-qualcosa",
	 * o esattamente "<old>". Ritorna null se il nome non ha il prefisso (quindi non va toccato).
	 */
	private static function replacePrefix($name, $oldPrefix, $newPrefix) {
		if ($oldPrefix === '') {
			return null;
		}
		if ($name === $oldPrefix) {
			return $newPrefix;
		}
		$prefixLen = strlen($oldPrefix);
		if (strncmp($name, $oldPrefix, $prefixLen) === 0) {
			$next = isset($name[$prefixLen]) ? $name[$prefixLen] : '';
			// Considera prefisso valido solo se seguito da separatore standard.
			if ($next === '_' || $next === '.' || $next === '-') {
				return $newPrefix . substr($name, $prefixLen);
			}
		}
		return null;
	}

	private static function listDirs($parent) {
		$result = array();
		if (!is_dir($parent)) {
			return $result;
		}
		$entries = @scandir($parent);
		if ($entries === false) {
			return $result;
		}
		foreach ($entries as $entry) {
			if ($entry === '.' || $entry === '..') {
				continue;
			}
			if (is_dir($parent . "/" . $entry)) {
				$result[] = $entry;
			}
		}
		return $result;
	}

	private static function executePlan($plan) {
		$results = array();
		foreach ($plan as $idx => $item) {
			$res = array(
				'id'         => $idx,
				'type'       => $item['type'],
				'old_path'   => $item['old_path'],
				'new_path'   => $item['new_path'],
				'final_path' => isset($item['final_path']) ? $item['final_path'] : $item['new_path'],
				'status'     => self::STATUS_SKIPPED,
				'message'    => '',
			);

			if (!file_exists($item['old_path'])) {
				$res['status']  = self::STATUS_SKIPPED;
				$res['message'] = "Sorgente non esiste (gia' rinominata in uno step precedente?).";
				$results[] = $res;
				continue;
			}

			// Step finale: merge ricorsivo del contenuto di old_path dentro new_path
			// (che esiste gia' nel 99% dei casi).
			if ($item['type'] === self::TYPE_ROOT_MERGE) {
				$errors = array();
				$moved = self::mergeDirectory($item['old_path'], $item['new_path'], $errors);
				$srcGone = !file_exists($item['old_path']);
				if (empty($errors)) {
					$res['status']  = self::STATUS_MERGED;
					$res['message'] = $moved . " elementi spostati"
						. ($srcGone ? "" : "; cartella sorgente non rimossa (non vuota o permessi).");
				} else {
					$res['status']  = self::STATUS_ERROR;
					$res['message'] = "Merge completato con errori (" . count($errors) . "): "
						. implode(' | ', array_slice($errors, 0, 5))
						. (count($errors) > 5 ? ' [...]' : '');
				}
				$results[] = $res;
				continue;
			}

			// Tutti gli altri step: rename atomico.
			if (file_exists($item['new_path'])) {
				$res['status']  = self::STATUS_SKIPPED;
				$res['message'] = "Destinazione gia' esistente: skip per evitare sovrascrittura.";
				$results[] = $res;
				continue;
			}

			$ok = @rename($item['old_path'], $item['new_path']);
			if ($ok) {
				$res['status']  = self::STATUS_RENAMED;
				$res['message'] = '';
			} else {
				$err = error_get_last();
				$res['status']  = self::STATUS_ERROR;
				$res['message'] = $err && isset($err['message']) ? $err['message'] : 'rename() ha restituito false.';
			}
			$results[] = $res;
		}
		return $results;
	}

	/**
	 * Sposta ricorsivamente tutto il contenuto di $srcDir dentro $dstDir, poi prova
	 * a rimuovere $srcDir se vuota.
	 *
	 * Regole:
	 *  - Se $dstDir non esiste viene creato.
	 *  - Per ogni elemento in $srcDir:
	 *      - destinazione assente             -> rename atomico (veloce)
	 *      - entrambe dir                     -> ricorsione (merge)
	 *      - entrambe file / tipi misti       -> conflitto, registrato in $errors,
	 *                                            il sorgente resta dov'e'.
	 *
	 * Ritorna il numero di elementi spostati con successo al primo livello
	 * (i merge ricorsivi contano come 1 elemento di primo livello).
	 */
	private static function mergeDirectory($srcDir, $dstDir, &$errors) {
		$moved = 0;
		if (!is_dir($srcDir)) {
			$errors[] = "Sorgente non e' una directory: $srcDir";
			return 0;
		}
		if (!is_dir($dstDir)) {
			if (!@mkdir($dstDir, 0775, true) && !is_dir($dstDir)) {
				$errors[] = "Impossibile creare destinazione: $dstDir";
				return 0;
			}
		}

		$entries = @scandir($srcDir);
		if ($entries === false) {
			$errors[] = "Impossibile leggere: $srcDir";
			return 0;
		}

		foreach ($entries as $entry) {
			if ($entry === '.' || $entry === '..') {
				continue;
			}
			$srcPath = $srcDir . '/' . $entry;
			$dstPath = $dstDir . '/' . $entry;

			if (!file_exists($dstPath)) {
				if (@rename($srcPath, $dstPath)) {
					$moved++;
				} else {
					$err = error_get_last();
					$errors[] = "rename $srcPath -> $dstPath: "
						. ($err && isset($err['message']) ? $err['message'] : 'errore sconosciuto');
				}
				continue;
			}

			if (is_dir($srcPath) && is_dir($dstPath)) {
				$before = count($errors);
				self::mergeDirectory($srcPath, $dstPath, $errors);
				if (count($errors) === $before) {
					$moved++;
				}
				continue;
			}

			$errors[] = "Conflitto, destinazione esistente: $dstPath";
		}

		// Tenta cleanup: rmdir riesce solo se ora $srcDir e' vuota.
		@rmdir($srcDir);
		return $moved;
	}

	/**
	 * Lettura STATION_NAME dal configuration.cfg di freeture.
	 * Stesso parser usato da CoreLogic::GetStationCode().
	 */
	private static function getStationName() {
		$freetureConf = _FREETURE_;
		$stationName = _DEFAULT_STATION_NAME_;

		if (file_exists($freetureConf) && is_file($freetureConf)) {
			$contents = file($freetureConf);
			foreach ($contents as $line) {
				if (isset($line) && $line !== "" && $line[0] !== "#" && $line[0] !== "\n" && $line[0] !== "\t" &&
						(strlen($line) - 1) !== substr_count($line, " ")) {
					if (CoreLogic::getKey($line) === "STATION_NAME") {
						$stationName = CoreLogic::getValue($line);
					}
				}
			}
		}
		return $stationName;
	}

	private static function isValidIdentifier($s) {
		return is_string($s) && $s !== '' && preg_match('/^[A-Za-z0-9._-]+$/', $s) === 1;
	}
}
