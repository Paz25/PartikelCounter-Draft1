<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Libraries\CoilService;

class Coil extends BaseController
{
    private CoilService $svc;

    public function __construct()
    {
        $this->svc = new CoilService(); // ambil config dari .env (opsional)
    }

    public function on()
    {
        try {
            $this->svc->setCoil(true);
            return $this->response->setJSON(['ok' => true, 'status' => 'ON']);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    public function off()
    {
        try {
            $this->svc->setCoil(false);
            return $this->response->setJSON(['ok' => true, 'status' => 'OFF']);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    public function status()
    {
        try {
            $st = $this->svc->getStatus(); // bisa null kalau lib tidak support read
            return $this->response->setJSON(['ok' => true, 'status' => $st]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['ok' => false, 'error' => $e->getMessage()]);
        }
    }
}
