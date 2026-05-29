<?php

/**
 * Manutenzione - Migrazione folder/file da configurazione DEFAULT a configurazione attuale.
 *
 * Quando un nodo PRISMA esce dalla fabbrica senza configurazione, freeture scrive in
 *   /freeture/DEFAULT/DEFAULT_YYYYMMDD/{captures,stacks,events}/DEFAULT_*.fit
 * Una volta arrivato sul campo e configurato (STATION_CODE / STATION_NAME impostati in
 * configuration.cfg), i nuovi dati finiscono in /freeture/<STATION_CODE>/<STATION_NAME>_YYYYMMDD/...
 * ma i dati storici rimangono orfani sotto DEFAULT.
 *
 * Questa logica:
 *  - Scansiona ricorsivamente /freeture/DEFAULT/ producendo un piano di rinomino
 *  - Applica il rinomino in ordine depth-first reverse (file -> event-folder -> day-folder)
 *    cosi i path parent restano validi finche il loro turno non arriva.
 *  - Step finale = merge ricorsivo (NON rename) del contenuto di /freeture/DEFAULT/ dentro
 *    /freeture/<STATION_CODE>/, perche' la destinazione esiste gia' nel 99% dei casi e
 *    rename() su destinazione esistente fallisce.
 *
 * @author: N3 S.r.l.
 */
class ManutenzioneApiLogic {

	// Token usato sia come STATION_CODE che STATION_NAME quando il nodo non e' configurato.
	const DEFAULT_TOKEN = 'DEFAULT';

	// Tipi item nel piano di migrazione.
	const TYPE_FILE       = 'file';
	const TYPE_EVENT_DIR  = 'event_dir';
	const TYPE_DAY_DIR    = 'day_dir';
	// L'ultimo step e' un merge ricorsivo del contenuto di /freeture/DEFAULT/ dentro
	// /freeture/<STATION_CODE>/ (NON un rename atomico), perche' la dir di destinazione
	// esiste quasi sempre gia' (freeture la crea appena configurato).
	const TYPE_ROOT_MERGE = 'root_merge';

	// Stati esecuzione.
	const STATUS_RENAMED = 'renamed';
	const STATUS_MERGED  = 'merged';
	const STATUS_SKIPPED = 'skipped';
	const STATUS_ERROR   = 'error';

	/**
	 * GET /manutenzione/migration/default/scan
	 * Ritorna { stationCode, stationName, defaultRoot, items: [...] }
	 * con il piano di rinomino senza applicarlo.
	 */
	public static function ScanDefaults() {
		try {
			$Person = CoreLogic::VerifyPerson();

			$stationCode = CoreLogic::GetStationCode();
			$stationName = self::getStationName();

			$payload = self::buildScanPayload($stationCode, $stationName);
		} catch (ApiException $a) {
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse(true, $payload);
	}

	/**
	 * POST /manutenzione/migration/default/run
	 * Esegue la migrazione e ritorna l'elenco dei risultati per ogni item.
	 */
	public static function RunMigration($request) {
		try {
			$Person = CoreLogic::VerifyPerson();
			CoreLogic::CheckCSRF($request->get("token"));

			$stationCode = CoreLogic::GetStationCode();
			$stationName = self::getStationName();

			if ($stationCode === self::DEFAULT_TOKEN || $stationName === self::DEFAULT_TOKEN
				|| $stationCode === '' || $stationName === '') {
				throw new ApiException(
					"La configurazione freeture corrente e' ancora DEFAULT (STATION_CODE=$stationCode, STATION_NAME=$stationName). " .
					"Configurare prima la stazione e ricaricare la pagina."
				);
			}

			if (!self::isValidIdentifier($stationCode) || !self::isValidIdentifier($stationName)) {
				throw new ApiException("STATION_CODE/STATION_NAME contengono caratteri non ammessi (consentiti solo [A-Za-z0-9._-]).");
			}

			$plan = self::buildPlan($stationCode, $stationName);
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

	private static function buildScanPayload($stationCode, $stationName) {
		$defaultRoot = rtrim(_FREETURE_DATA_, "/") . "/" . self::DEFAULT_TOKEN;
		$configIsValid = ($stationCode !== self::DEFAULT_TOKEN && $stationName !== self::DEFAULT_TOKEN
						&& $stationCode !== '' && $stationName !== '');

		$payload = array(
			'stationCode'    => $stationCode,
			'stationName'    => $stationName,
			'defaultRoot'    => $defaultRoot,
			'rootExists'     => is_dir($defaultRoot),
			'configIsValid'  => $configIsValid,
			'items'          => array(),
		);

		if (!$payload['rootExists']) {
			return $payload;
		}

		// Se la config non e' ancora valida costruiamo comunque la preview usando placeholder
		// testuali, cosi l'utente vede l'elenco di cosa verra' rinominato (in sola lettura).
		$planCode = $configIsValid ? $stationCode : '<STATION_CODE>';
		$planName = $configIsValid ? $stationName : '<STATION_NAME>';

		$plan = self::buildPlan($planCode, $planName);
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
	 * quindi 'new_path' contiene "DEFAULT/..." sui parent. 'final_path' applica anche
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
	 * Regole di prefisso (context-dependent):
	 *   - root folder       -> STATION_CODE
	 *   - day folder        -> STATION_CODE
	 *   - captures/*.fit    -> STATION_CODE
	 *   - stacks/*.fit      -> STATION_NAME
	 *   - events/<evt>/     -> STATION_NAME (nome cartella evento)
	 *   - events/<evt>/*    -> STATION_NAME (file dentro la cartella evento)
	 */
	private static function buildPlan($stationCode, $stationName) {
		$plan = array();
		$root = rtrim(_FREETURE_DATA_, "/") . "/" . self::DEFAULT_TOKEN;
		if (!is_dir($root)) {
			return $plan;
		}

		// 1) Day folders sotto la root DEFAULT.
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
						self::collectDefaultFiles($eventPath, $stationName, $plan);
						$newEventName = self::replacePrefix($eventDir, $stationName);
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
					self::collectDefaultFiles($subPath, $stationCode, $plan);
				} elseif ($subDir === 'stacks') {
					// stacks/*.fit -> prefisso STATION_NAME.
					self::collectDefaultFiles($subPath, $stationName, $plan);
				} else {
					// Sub-folder generiche (logs/, sessions/, ...): fallback su STATION_NAME.
					self::collectDefaultFiles($subPath, $stationName, $plan);
				}
			}

			// File DEFAULT_* direttamente nella day folder (raro): fallback su STATION_CODE
			// per coerenza con il nome della day folder a cui appartengono.
			self::collectDefaultFiles($dayPath, $stationCode, $plan);

			// 3) Day folder DEFAULT_YYYYMMDD -> STATION_CODE_YYYYMMDD.
			$newDayName = self::replacePrefix($dayDir, $stationCode);
			if ($newDayName !== null && $newDayName !== $dayDir) {
				$plan[] = array(
					'type'     => self::TYPE_DAY_DIR,
					'old_path' => $dayPath,
					'new_path' => $root . "/" . $newDayName,
				);
			}
		}

		// 4) File DEFAULT_* direttamente sotto /freeture/DEFAULT/ (raro): STATION_CODE.
		self::collectDefaultFiles($root, $stationCode, $plan);

		// 5) Root: merge ricorsivo /freeture/DEFAULT/* -> /freeture/<STATION_CODE>/* e rmdir DEFAULT.
		$plan[] = array(
			'type'     => self::TYPE_ROOT_MERGE,
			'old_path' => $root,
			'new_path' => rtrim(_FREETURE_DATA_, "/") . "/" . $stationCode,
		);

		return $plan;
	}

	private static function collectDefaultFiles($dirPath, $stationName, &$plan) {
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
			$newName = self::replacePrefix($entry, $stationName);
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
	 * Restituisce il nome rinominato sostituendo il prefisso "DEFAULT" con $newPrefix.
	 * Match esatto del prefisso: "DEFAULT_qualcosa", "DEFAULT.qualcosa", o esattamente "DEFAULT".
	 * Ritorna null se il nome non ha prefisso DEFAULT (quindi non va toccato).
	 */
	private static function replacePrefix($name, $newPrefix) {
		if ($name === self::DEFAULT_TOKEN) {
			return $newPrefix;
		}
		$prefixLen = strlen(self::DEFAULT_TOKEN);
		if (strncmp($name, self::DEFAULT_TOKEN, $prefixLen) === 0) {
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
