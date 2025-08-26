<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PartikelCounterBufferModel;
use App\Models\PartikelCounterDataModel;

class PartikelController extends BaseController
{

    public function saveBuffer()
    {
        $bufferModel = new PartikelCounterBufferModel();
        $dataModel   = new PartikelCounterDataModel();

        $oldBuffer = $bufferModel->first();

        if ($oldBuffer) {
            $dataModel->insert($oldBuffer);

            // $bufferModel->truncate();
        }

        $newData = [
            'mac_address'  => $this->request->getPost('mac_address'),
            'waktu'  => $this->request->getPost('waktu'),
            'SignalDb' => $this->request->getPost('SignalDb'),
            'Value03' => $this->request->getPost('Value03'),
            'Limit03' => $this->request->getPost('Limit03'),
            'Value05' => $this->request->getPost('Value05'),
            'Limit05' => $this->request->getPost('Limit05'),
            'Value10' => $this->request->getPost('Value10'),
            'Limit10' => $this->request->getPost('Limit10'),
            'Value25' => $this->request->getPost('Value25'),
            'Limit25' => $this->request->getPost('Limit25'),
            'Value50' => $this->request->getPost('Value50'),
            'Limit50' => $this->request->getPost('Limit50'),
            'Value100' => $this->request->getPost('Value100'),
            'Limit100' => $this->request->getPost('Limit100'),
            'Status' => $this->request->getPost('Status'),
            'port_ke' => $this->request->getPost('port_ke'),
            'user' => $this->request->getPost('user'),
        ];
        $bufferModel->insert($newData);

        return $this->response->setJSON(['message' => 'Data buffer updated successfully']);
    }
}
