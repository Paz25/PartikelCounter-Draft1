<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\PartikelReader;
use App\Models\PartikelCounterBufferModel;

class ReadPartikel extends BaseCommand
{
    protected $group = 'Custom';
    protected $name = 'read:partikel';
    protected $description = 'Baca sensor partikel via Modbus dan simpan ke DB';

    public function run(array $params)
    {
        $reader = new PartikelReader();
        $model = new PartikelCounterBufferModel();

        while (true) {
            $regs = $reader->bacaSensor();

            if ($regs) {
                $data = $reader->parseData($regs);
                $model->insert($data);

                CLI::write("0.3um = {$data['Value03']} pcs/L", 'green');
                CLI::write("0.5um = {$data['Value05']} pcs/L", 'green');
                CLI::write("1.0um = {$data['Value10']} pcs/L", 'green');
                CLI::write("5.0um = {$data['Value50']} pcs/L", 'green');
                CLI::write("Data tersimpan ke DB!", 'yellow');
            } else {
                CLI::error("Tidak bisa baca data sensor");
            }

            sleep(10);
        }
    }
}
