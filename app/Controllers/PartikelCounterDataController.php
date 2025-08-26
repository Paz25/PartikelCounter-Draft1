<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class PartikelCounterDataController extends ResourceController
{
    protected $modelName = 'App\Models\PartikelCounterDataModel';
    protected $format    = 'json';

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $start = $this->request->getGet('start');
        $end   = $this->request->getGet('end');
        $status = $this->request->getGet('status');

        $model = new \App\Models\PartikelCounterDataModel();
        $data  = [
            'message' => 'success',
            'data'    => $model->getFilteredData($start, $end, $status),
        ];

        return $this->respond($data, 200);
    }


    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
    }
}
