<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;

class PartikelController extends BaseController
{
    protected $pidFile = WRITEPATH . 'partikel.pid';

    public function start()
    {
        log_message('debug', "PartikelController::start called");
        if (file_exists($this->pidFile)) {
            $pid = (int) file_get_contents($this->pidFile);
            $running = shell_exec("ps -p $pid");
            if ($running) {
                log_message('debug', "Process still running with PID $pid");
                return $this->response->setJSON(['status' => false, 'message' => 'Already running']);
            } else {
                log_message('debug', "PID file exists but process not running, removing PID file");
                unlink($this->pidFile);
            }
        }

        $cmd = "start /B php " . ROOTPATH . "spark read:partikel > " . WRITEPATH . "partikel.log 2>&1";
        log_message('debug', "Command: $cmd");
        shell_exec($cmd);
        log_message('debug', "PID returned: $pid");

        if ($pid) {
            file_put_contents($this->pidFile, trim($pid));
            return $this->response->setJSON(['status' => true]);
        } else {
            log_message('error', "Failed to execute shell command");
            return $this->response->setJSON(['status' => false, 'message' => 'Failed to start']);
        }
    }

    public function stop()
    {
        if (!file_exists($this->pidFile)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Not running']);
        }

        // buat flag stop
        file_put_contents(WRITEPATH . 'partikel.stop', '1');

        // optional: tunggu sebentar agar proses baca flag
        sleep(1);

        // hapus pid file
        unlink($this->pidFile);

        return $this->response->setJSON(['status' => true]);
    }


    public function status()
    {
        $running = file_exists(WRITEPATH . 'partikel.pid'); // pid file ada → sedang berjalan
        return $this->response->setJSON([
            'status' => $running,             // true = sedang mengambil data, false = idle
            'message' => $running ? 'Collecting Data' : 'Idle'
        ]);
    }

}
