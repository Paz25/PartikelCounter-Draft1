<?php

namespace App\Models;

use CodeIgniter\Model;

class IsoLimitsModel extends Model
{
    protected $table = 'iso_limits';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'iso_class',
        'Limit03',
        'Limit05',
        'Limit10',
        'Limit25',
        'Limit50',
        'Limit100'
    ];

    protected $returnType = 'array';
    public $useTimestamps = false;
}
