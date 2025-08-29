<?php
namespace App\Libraries;

require_once dirname(__FILE__, 2) . '/ThirdParty/phpmodbus/Phpmodbus/ModbusMaster.php';

use ModbusMaster;

class PartikelReader
{
    protected $ip = "192.168.16.254";
    protected $port = 502;
    protected $readStart = 0;
    protected $readCount = 40;

    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

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

    public function parseData($regs)
    {
        $words = [];
        for ($i = 0; $i < count($regs); $i += 2) {
            $words[] = ($regs[$i] << 8) | $regs[$i + 1];
        }

        return [
            'Value03' => ($words[8] << 16) | $words[9],
            'Value05' => ($words[10] << 16) | $words[11],
            'Value10' => ($words[12] << 16) | $words[13],
            'Value50' => ($words[14] << 16) | $words[15],
        ];
    }

    public function evaluasiISO(array $values, int $isoClass)
    {
        $limit = $this->db->table('iso_limits')
            ->where('iso_class', $isoClass)
            ->get()
            ->getRowArray();

        if (!$limit) {
            return ['Status' => 'Unknown', 'iso_class' => null] + $values;
        }

        $status = "Normal";
        foreach ([
            'Value03' => 'Limit03',
            'Value05' => 'Limit05',
            'Value10' => 'Limit10',
            'Value25' => 'Limit25',
            'Value50' => 'Limit50',
            'Value100' => 'Limit100'
        ] as $valKey => $limitKey) {
            $val = $values[$valKey] ?? null;
            $lim = $limit[$limitKey] ?? null;

            if ($lim !== null && $val !== null && $val > $lim) {
                $status = "Alarm";
                break;
            }
        }

        return [
            'Status' => $status,
            'iso_class' => $limit['id'],
        ] + $values;
    }
}
