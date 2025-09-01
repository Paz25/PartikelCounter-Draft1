<?php

namespace App\Models;
use CodeIgniter\Model;

class SensorStatusModel extends Model
{
    protected $table = 'sensor_status';
    protected $primaryKey = 'id';
    protected $allowedFields = ['status'];
    protected $useTimestamps = true;
}

