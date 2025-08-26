<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Particle extends BaseController
{
    public function index()
    {
       $data = [
            'device_ip'      => '10.8.160.32',
            'rows' => [
                ['label' => '> 0.3', 'value' => 101874, 'limit' => 0],
                ['label' => '> 0.5', 'value' => 30035,  'limit' => 352000],
                ['label' => '> 1.0', 'value' => 1186,   'limit' => 83200],
                ['label' => '> 5.0', 'value' => 60,     'limit' => 2930],
            ],
            'iso_class'      => 7,
            'sampling_total' => 500,
            'sampling_now'   => 478,
            'elapsed_sec'    => 846,
            'trigger_mode'   => 'auto',
            'cycle'          => 300,
        ];
        return view('particle/index.php', $data);
    }
}
