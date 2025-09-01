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
    protected $description = 'Baca sensor partikel via Modbus dan simpan ke DB dengan evaluasi ISO';

    public function run(array $params)
    {
        $reader = new PartikelReader();
        $model = new PartikelCounterBufferModel();

        $isoClass = $params[0] ?? 7;

        $timeoutSeconds = 10;
        $startTime = time();

        $stopFile = WRITEPATH . 'partikel.stop';

        while (true) {
            if (file_exists($stopFile)) {
                CLI::write("Stop signal diterima, menghentikan proses.", 'red');
                unlink($stopFile); // hapus flag
                break;
            }


            $isOn = $reader->cekCoilStatus(1, 0x001E);

            if (!$isOn) {
                CLI::error("Sensor belum ON, tunggu ...");

                if ((time() - $startTime) >= $timeoutSeconds) {
                    CLI::error("Sensor tidak menyala selama {$timeoutSeconds} detik. Proses dihentikan.");
                    break;
                }

                sleep(5);
                continue;
            }

            // reset startTime jika sensor sudah ON
            $startTime = time();

            // sensor ON → lanjut baca data
            $regs = $reader->bacaSensor();

            if ($regs) {
                $values = $reader->parseData($regs);

                $evaluated = $reader->evaluasiISO($values, $isoClass);
                $model->insert($evaluated);

                CLI::write("ISO Class: {$evaluated['iso_class']}", 'yellow');
                CLI::write(
                    "Status: {$evaluated['Status']}",
                    $evaluated['Status'] === "Alarm" ? 'red' : 'green'
                );
                CLI::write("0.3um = {$evaluated['Value03']} pcs/L", 'green');
                CLI::write("0.5um = {$evaluated['Value05']} pcs/L", 'green');
                CLI::write("1.0um = {$evaluated['Value10']} pcs/L", 'green');
                CLI::write("5.0um = {$evaluated['Value50']} pcs/L", 'green');
                CLI::write("Data tersimpan ke DB!", 'yellow');
            } else {
                CLI::error("Tidak bisa baca data sensor");
            }

            sleep(10);
        }
    }
}
