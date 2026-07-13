<?php

class SshkeysController extends Controller {

    const AUTHORIZED_KEYS_PATH = '/keys/authorized_keys';

    public function editOperation() {
        parent::securityCheck(1); // Admin
        global $SshKeys;
        $SshKeys = $this->readKeys();
    }

    public function addOperation() {
        parent::securityCheck(1);
        $resp = new Response();

        $key = isset($_POST['pubkey']) ? trim($_POST['pubkey']) : '';
        $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

        if (empty($key)) {
            $resp->success = false;
            $resp->content = _('Chiave pubblica non valida');
            return $resp;
        }

        // Validazione formato base chiave SSH pubblica
        if (!preg_match('/^(ssh-rsa|ssh-ed25519|ecdsa-sha2-nistp256|ecdsa-sha2-nistp384|ecdsa-sha2-nistp521)\s+[A-Za-z0-9+\/]+=*(\s+.*)?$/', $key)) {
            $resp->success = false;
            $resp->content = _('Formato chiave non riconosciuto. Usare ssh-rsa, ssh-ed25519 o ecdsa-sha2-*');
            return $resp;
        }

        // Aggiunge il commento alla chiave se fornito separatamente
        $keyLine = $key;
        if (!empty($comment) && substr_count($key, ' ') < 2) {
            $keyLine = $key . ' ' . $comment;
        }

        $keys = $this->readKeys();
        // Controlla duplicati (confronto sul blob base64)
        $newBlob = explode(' ', $keyLine)[1] ?? '';
        foreach ($keys as $existing) {
            $existingBlob = explode(' ', $existing)[1] ?? '';
            if ($newBlob && $newBlob === $existingBlob) {
                $resp->success = false;
                $resp->content = _('Chiave già presente');
                return $resp;
            }
        }

        $keys[] = $keyLine;
        $written = $this->writeKeys($keys);

        if (!$written) {
            $resp->success = false;
            $resp->content = _('Errore scrittura file authorized_keys');
            return $resp;
        }

        $resp->success = true;
        $resp->content = _('Chiave aggiunta');
        return $resp;
    }

    public function deleteOperation() {
        parent::securityCheck(1);
        $resp = new Response();

        $blob = isset($_POST['blob']) ? trim($_POST['blob']) : '';
        if (empty($blob)) {
            $resp->success = false;
            $resp->content = _('Parametro mancante');
            return $resp;
        }

        $keys = $this->readKeys();
        $filtered = array_filter($keys, function($line) use ($blob) {
            $parts = explode(' ', $line);
            return ($parts[1] ?? '') !== $blob;
        });

        if (count($filtered) === count($keys)) {
            $resp->success = false;
            $resp->content = _('Chiave non trovata');
            return $resp;
        }

        $written = $this->writeKeys(array_values($filtered));
        if (!$written) {
            $resp->success = false;
            $resp->content = _('Errore scrittura file authorized_keys');
            return $resp;
        }

        $resp->success = true;
        $resp->content = _('Chiave rimossa');
        return $resp;
    }

    public function listOperation() {
        parent::securityCheck(1);
        $resp = new Response();
        $keys = $this->readKeys();
        $result = array_map(function($line) {
            $parts = explode(' ', $line, 3);
            return [
                'type'    => $parts[0] ?? '',
                'blob'    => $parts[1] ?? '',
                'comment' => $parts[2] ?? '',
                'full'    => $line,
            ];
        }, $keys);
        $resp->content = json_encode($result);
        return $resp;
    }

    // ---- helpers ----

    private function readKeys(): array {
        $path = self::AUTHORIZED_KEYS_PATH;
        if (!file_exists($path)) {
            return [];
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) return [];
        // Esclude righe commento
        return array_values(array_filter($lines, function($l) {
            return strpos(ltrim($l), '#') !== 0;
        }));
    }

    private function writeKeys(array $keys): bool {
        $path = self::AUTHORIZED_KEYS_PATH;
        $dir = dirname($path);
        if (!is_dir($dir)) {
            return false;
        }
        $content = implode("\n", $keys);
        if (!empty($content)) {
            $content .= "\n";
        }
        return file_put_contents($path, $content, LOCK_EX) !== false;
    }
}
