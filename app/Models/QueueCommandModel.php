<?php

namespace App\Models;
use CodeIgniter\Model;

class QueueCommandModel extends Model
{
    protected $table = 'queue_commands';
    protected $allowedFields = ['command', 'processed'];
    protected $useTimestamps = true;
}