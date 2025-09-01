<?php
namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\PartikelCounterBufferModel;
use App\Models\PartikelCounterDataModel;

class PartikelCounterDataController extends ResourceController
{
    protected $modelName = 'App\Models\PartikelCounterDataModel';
    protected $format = 'json';

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');
        $status = $this->request->getGet('status');

        $model = new \App\Models\PartikelCounterDataModel();
        $data = [
            'message' => 'success',
            'data' => $model->getFilteredData($start, $end, $status),
        ];

        return $this->respond($data, 200);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $bufferModel = new PartikelCounterBufferModel();
        $dataModel = new PartikelCounterDataModel();

        $oldBuffer = $bufferModel->first();

        if ($oldBuffer) {
            $temp = [
                'mac_address' => $oldBuffer['mac_address'],
                'waktu' => $oldBuffer['waktu'],
                'Value03' => $oldBuffer['Value03'],
                'Limit03' => $oldBuffer['Limit03'],
                'Value05' => $oldBuffer['Value05'],
                'Limit05' => $oldBuffer['Limit05'],
                'Value10' => $oldBuffer['Value10'],
                'Limit10' => $oldBuffer['Limit10'],
                'Value25' => $oldBuffer['Value25'],
                'Limit25' => $oldBuffer['Limit25'],
                'Value50' => $oldBuffer['Value50'],
                'Limit50' => $oldBuffer['Limit50'],
                'Value100' => $oldBuffer['Value100'],
                'Limit100' => $oldBuffer['Limit100'],
                'Status' => $oldBuffer['Status'],
                'port_ke' => $oldBuffer['port_ke'],
                'user' => $oldBuffer['user'],
            ];
            $dataModel->insert($temp);

            // $bufferModel->truncate();
        }

        $newData = [
            'mac_address' => $this->request->getPost('mac_address'),
            'waktu' => $this->request->getPost('waktu'),
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
