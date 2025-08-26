<?php

namespace App\Models;

use CodeIgniter\Model;

class PartikelCounterBufferModel extends Model
{
    protected $table            = 'partikel_counter_buffer';
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
        'valueReal',
    ];
}
