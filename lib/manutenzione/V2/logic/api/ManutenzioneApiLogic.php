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
 *  - Applica il rinomino in ordine depth-first reverse (file -> event-folder -> day-folder -> root)
 *    cosi i path parent restano validi finche il loro turno non arriva.
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
	const TYPE_ROOT_DIR   = 'root_dir';

	// Stati esecuzione.
	const STATUS_RENAMED = 'renamed';
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

		// 5) Root: /freeture/DEFAULT -> /freeture/<STATION_CODE>.
		$plan[] = array(
			'type'     => self::TYPE_ROOT_DIR,
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
				'id'       => $idx,
				'type'     => $item['type'],
				'old_path' => $item['old_path'],
				'new_path' => $item['new_path'],
				'status'   => self::STATUS_SKIPPED,
				'message'  => '',
			);

			if (!file_exists($item['old_path'])) {
				$res['status']  = self::STATUS_SKIPPED;
				$res['message'] = "Sorgente non esiste (gia' rinominata in uno step precedente?).";
				$results[] = $res;
				continue;
			}

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
