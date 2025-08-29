<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CoilControl extends BaseCommand
{
    protected $group = 'sensor';
    protected $name = 'sensor:coil';
    protected $description = 'Nyalakan / matikan coil Modbus TCP dan cek status (PHPModbus).';
    protected $usage = "php spark sensor:coil [--on] [--off] [--status]";

    public function run(array $params)
    {
        // 1) Muat library (dari app/ThirdParty)
        require_once APPPATH . 'ThirdParty/phpmodbus/Phpmodbus/ModbusMaster.php';

        // 2) Ambil config (.env) atau fallback default
        $ip = env('modbus.ip', '192.168.16.254');
        $port = (int) env('modbus.port', 502);
        $unitId = max(0, min(247, (int) env('modbus.unitId', 1)));
        $coilStr = env('modbus.coil', '0x01E');
        $coil = strncasecmp($coilStr, '0x', 2) === 0 ? intval($coilStr, 16) : (int) $coilStr;

        // 3) Buat instance ModbusMaster / ModbusMasterTcp (tergantung fork)
        if (class_exists('ModbusMaster')) {
            $modbus = new \ModbusMaster($ip, "TCP");
        } elseif (class_exists('ModbusMasterTcp')) {
            $modbus = new \ModbusMasterTcp($ip);
        } else {
            CLI::error('Kelas ModbusMaster tidak ditemukan. Cek path: app/ThirdParty/phpmodbus/Phpmodbus/ModbusMaster.php');
            return;
        }
        if (property_exists($modbus, 'port'))
            $modbus->port = $port;
        if (property_exists($modbus, 'timeout_sec'))
            $modbus->timeout_sec = 3;

        // 4) Helper ON/OFF/READ
        $setCoil = function (bool $on) use ($modbus, $unitId, $coil) {
            if (method_exists($modbus, 'writeSingleCoil')) {
                $modbus->writeSingleCoil($unitId, $coil, $on);
            } elseif (method_exists($modbus, 'writeMultipleCoils')) {
                $modbus->writeMultipleCoils($unitId, $coil, [$on]);
            } else {
                throw new \RuntimeException('writeSingleCoil / writeMultipleCoils tidak tersedia di fork PHPModbus ini.');
            }
        };
        $readCoil = function () use ($modbus, $unitId, $coil): ?bool {
            if (!method_exists($modbus, 'readCoils'))
                return null;
            $resp = $modbus->readCoils($unitId, $coil, 1);
            return (is_array($resp) && array_key_exists(0, $resp)) ? (bool) $resp[0] : null;
        };

        // 5) Mode non-interaktif via flag
        $flags = implode(' ', $params);
        try {
            if (strpos($flags, '--on') !== false) {
                CLI::write('Alarm ON', 'green');
                $setCoil(true);
                return;
            }
            if (strpos($flags, '--off') !== false) {
                CLI::write('Alarm OFF', 'yellow');
                $setCoil(false);
                return;
            }
            if (strpos($flags, '--status') !== false) {
                $st = $readCoil();
                CLI::write('Status: ' . ($st ? 'ON' : 'OFF'));
                return;
            }

            // 6) Mode interaktif (menu sederhana)
            while (true) {
                CLI::write("");
                CLI::write("IP={$ip}:{$port}  unitId={$unitId}  coil={$coil}");
                CLI::write("1) ON  |  2) OFF  |  3) STATUS  |  0) Keluar");
                $opt = CLI::prompt('Pilih', '3');

                if ($opt === '1') {
                    CLI::write('Alarm ON', 'green');
                    $setCoil(true);
                } elseif ($opt === '2') {
                    CLI::write('Alarm OFF', 'yellow');
                    $setCoil(false);
                } elseif ($opt === '3') {
                    $st = $readCoil();
                    CLI::write('Status: ' . ($st ? 'ON' : 'OFF'));
                } elseif ($opt === '0') {
                    CLI::write('Keluar.', 'blue');
                    break;
                } else {
                    CLI::error('Pilihan tidak valid.');
                }
            }
        } catch (\Throwable $e) {
            CLI::error('ERR: ' . $e->getMessage());
        }
    }
}
