<?php

namespace App\Models;

use CodeIgniter\Model;

class PartikelCounterDataModel extends Model
{
    protected $table            = 'partikel_counter_data';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'mac_address',
        'waktu',
        'SignalDb',
        'Value03',
        'Limit03',
        'Value05',
        'Limit05',
        'Value10',
        'Limit10',
        'Value25',
        'Limit25',
        'Value50',
        'Limit50',
        'Value100',
        'Limit100',
        'Status',
        'port_ke',
        'user',
    ];

    public function getFilteredData($start = null, $end = null, $status = null)
    {
        $builder = $this->builder();

        if ($start && $end) {
            $builder->where('tanggal >=', $start);
            $builder->where('tanggal <=', $end);
        }

        if ($status) {
            $builder->where('status', $status);
        }

        return $builder->get()->getResultArray();
    }
}
