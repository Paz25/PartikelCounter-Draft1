<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\QueueCommandModel;
use App\Models\SensorStatusModel;

class PartikelController extends BaseController
{
    public function start()
    {
        $queue = new QueueCommandModel();
        $queue->insert(['command' => 'start']);

        return $this->response->setJSON(['status' => 'queued start']);
    }

    public function stop()
    {
        $queue = new QueueCommandModel();
        $queue->insert(['command' => 'stop']);

        return $this->response->setJSON(['status' => 'queued stop']);
    }

    public function status()
    {
        $statusModel = new SensorStatusModel();
        $status = $statusModel->find(1);

        return $this->response->setJSON(['status' => $status['status']]);
    }
}

