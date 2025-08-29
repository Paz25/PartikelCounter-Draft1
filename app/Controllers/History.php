<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class History extends BaseController
{
    public function index()
    {
        return view('history/index.php');
    }
}
