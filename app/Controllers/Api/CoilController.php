<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Libraries\CoilService;
use App\Libraries\PartikelReader;
use App\Models\PartikelCounterBufferModel;

class CoilController extends BaseController
{
    private CoilService $svc;
    private $reader;
    private $buffer;

    public function __construct()
    {
        $this->svc = new CoilService();
        $this->reader = new PartikelReader();
        $this->buffer = new PartikelCounterBufferModel();
    }

    public function on()
    {
        try {
            $this->svc->setCoil(true);
            cache()->save('coil_status', 'ON', 0);
            $this->startReading();
            return $this->response->setJSON(['ok' => true, 'status' => 'ON']);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    public function off()
    {
        try {
            $this->svc->setCoil(false);
            cache()->save('coil_status', 'OFF', 0); // simpan status di cache
            return $this->response->setJSON(['ok' => true, 'status' => 'OFF']);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    public function status()
    {
        try {
            $st = cache('coil_status'); // ambil dari cache
            return $this->response->setJSON(['ok' => true, 'status' => $st]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    private function startReading()
    {
        $cmd = 'php spark read:partikel > /dev/null 2>&1 &';
        exec($cmd);
    }
}
