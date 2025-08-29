<?php
namespace App\Libraries;

require_once dirname(__FILE__, 2) . '/ThirdParty/phpmodbus/Phpmodbus/ModbusMaster.php';

use ModbusMaster;
use mysqli;

class PartikelReader
{
    protected $ip = "192.168.16.254";
    protected $port = 502;
    protected $readStart = 0;
    protected $readCount = 40;

    // baca sensor via modbus
    public function bacaSensor()
    {
        try {
            $modbus = new ModbusMaster($this->ip, "TCP");
            $modbus->port = $this->port;
            $regs = $modbus->readMultipleRegisters(1, $this->readStart, $this->readCount);
            return $regs;
        } catch (\Exception $e) {
            log_message('error', "Modbus error: " . $e->getMessage());
            return null;
        }
    }

    // parsing data
    public function parseData($regs)
    {
        $words = [];
        for ($i = 0; $i < count($regs); $i += 2) {
            $words[] = ($regs[$i] << 8) | $regs[$i + 1];
        }

        $val03 = ($words[8] << 16) | $words[9];
        $val05 = ($words[10] << 16) | $words[11];
        $val10 = ($words[12] << 16) | $words[13];
        $val50 = ($words[14] << 16) | $words[15];

        return [
            'Value03' => $val03,
            'Value05' => $val05,
            'Value10' => $val10,
            'Value50' => $val50,
        ];
    }
}
