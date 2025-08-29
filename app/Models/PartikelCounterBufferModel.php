<?php

namespace App\Models;

use CodeIgniter\Model;

class PartikelCounterBufferModel extends Model
{
    protected $table = 'partikel_counter_buffer';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'mac_address',
        'waktu',
        'SignalDb',
        'Value03',
        'Value05',
        'Value10',
        'Value25',
        'Value50',
        'Value100',
        'iso_class',
        'Status',
        'port_ke',
        'user',
        'valueReal',
    ];
}
