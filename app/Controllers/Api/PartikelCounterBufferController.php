<?php
namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class PartikelCounterBufferController extends ResourceController
{
    protected $modelName = 'App\Models\PartikelCounterBufferModel';
    protected $format = 'json';

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $lastData = $this->model
            ->orderBy('id', 'DESC')
            ->first();

        $data = [
            'message' => 'success',
            'data' => $lastData,
        ];

        return $this->respond($data, 200);
    }

}
