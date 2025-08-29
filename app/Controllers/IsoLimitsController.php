<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\IsoLimitsModel;

class IsoLimitsController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $model = new IsoLimitsModel();
        $rows = $model->findAll();

        return $this->respond([
            'status' => 'success',
            'data' => $rows
        ]);
    }

    public function show($isoClass = null)
    {
        if ($isoClass === null) {
            return $this->fail('ISO class parameter is required', 400);
        }

        $model = new IsoLimitsModel();
        $row = $model->where('iso_class', $isoClass)->first();

        if (!$row) {
            return $this->failNotFound("ISO Class {$isoClass} not found");
        }

        return $this->respond([
            'status' => 'success',
            'data' => $row
        ]);
    }
}