<?php
namespace App\Libraries;

class PartikelJob
{
    private string $pidFile;
    private string $stopFile;
    private string $logFile;
    private string $spark;

    public function __construct()
    {
        $this->pidFile = WRITEPATH . 'partikel.pid';
        $this->stopFile = WRITEPATH . 'partikel.stop';
        $this->logFile = WRITEPATH . 'partikel.log';
        $this->spark = ROOTPATH . 'spark';
    }

    public function start(): bool
    {
        // jika sudah dianggap running, jangan dobel start
        if ($this->isRunning()) {
            return true;
        }

        if (PHP_OS_FAMILY === 'Windows') {
            // Windows: detach benar + judul kosong wajib ("")
            $cmd = 'cmd /c start "" /B php "' . $this->spark . '" read:partikel >> "' . $this->logFile . "\" 2>&1";
            // Detach: jangan pakai shell_exec() yang nunggu output
            // popen/pclose membuatnya lepas dari proses caller
            @pclose(@popen($cmd, 'r'));
            // Tidak mudah ambil PID di Windows → pakai marker file saja
            @file_put_contents($this->pidFile, 'win');
            return true;
        } else {
            // Linux/Unix: ambil PID dengan nohup & echo $!
            $cmd = 'nohup php ' . escapeshellarg($this->spark) . ' read:partikel >> ' . escapeshellarg($this->logFile) . ' 2>&1 & echo $!';
            $pid = @shell_exec($cmd);
            if ($pid && is_numeric(trim($pid))) {
                file_put_contents($this->pidFile, trim($pid));
                return true;
            }
            return false;
        }
    }

    public function stop(): bool
    {
        // Selalu kirim sinyal stop via file flag
        @file_put_contents($this->stopFile, '1');

        // Di *nix, jika ada PID kita kirim kill (graceful)
        if (PHP_OS_FAMILY !== 'Windows' && file_exists($this->pidFile)) {
            $pid = (int) trim(@file_get_contents($this->pidFile));
            if ($pid > 0) {
                @shell_exec('kill ' . $pid . ' 2>/dev/null');
            }
        }

        @unlink($this->pidFile);
        return true;
    }

    public function isRunning(): bool
    {
        if (!file_exists($this->pidFile)) {
            return false;
        }

        if (PHP_OS_FAMILY === 'Windows') {
            // Heuristik sederhana: ada pidFile → dianggap running
            // (opsional: bisa cari proses php.exe + argumen "read:partikel" via `wmic` atau PowerShell)
            return true;
        }

        // *nix: verifikasi PID masih hidup
        $pid = (int) trim(@file_get_contents($this->pidFile));
        if ($pid <= 0)
            return false;
        $out = @shell_exec('ps -p ' . $pid . ' -o pid=');
        return trim((string) $out) === (string) $pid;
    }
}