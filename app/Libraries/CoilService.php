<?php
namespace App\Libraries;

class CoilService
{
    private string $ip;
    private int $port;
    private int $unitId;
    private int $coil;
    private int $timeout = 3;

    public function __construct(
        string $ip      = null,
        int    $port    = null,
        int    $unitId  = null,
        $coil           = null
    ) {
        // Bisa ambil dari .env; kalau tidak ada pakai default
        $this->ip     = $ip     ?? env('modbus.ip', '192.168.16.254');
        $this->port   = $port   ?? (int) env('modbus.port', 502);
        $this->unitId = $unitId ?? max(0, min(247, (int) env('modbus.unitId', 1)));

        $coilStr      = $coil ?? env('modbus.coil', '0x01E');
        $this->coil   = (strncasecmp((string)$coilStr, '0x', 2) === 0) ? intval($coilStr, 16) : (int) $coilStr;

        // load library PHPModbus once
        require_once APPPATH . 'ThirdParty/phpmodbus/Phpmodbus/ModbusMaster.php';
    }

    private function newDriver()
    {
        if (class_exists('\ModbusMaster'))      $m = new \ModbusMaster($this->ip, 'TCP');
        elseif (class_exists('\ModbusMasterTcp')) $m = new \ModbusMasterTcp($this->ip);
        else throw new \RuntimeException('ModbusMaster tidak ditemukan. Cek app/ThirdParty/phpmodbus');

        if (property_exists($m, 'port'))        $m->port = $this->port;
        if (property_exists($m, 'timeout_sec')) $m->timeout_sec = $this->timeout;
        return $m;
    }

    public function setCoil(bool $on): void
    {
        $m = $this->newDriver();
        if (method_exists($m, 'writeSingleCoil')) {
            $m->writeSingleCoil($this->unitId, $this->coil, $on);
        } elseif (method_exists($m, 'writeMultipleCoils')) {
            $m->writeMultipleCoils($this->unitId, $this->coil, [$on]);
        } else {
            throw new \RuntimeException('writeSingleCoil/MultipleCoils tidak tersedia pada library ini.');
        }
    }

    public function getStatus(): ?bool
    {
        $m = $this->newDriver();
        if (!method_exists($m, 'readCoils')) return null;
        $resp = $m->readCoils($this->unitId, $this->coil, 1);
        return (is_array($resp) && array_key_exists(0, $resp)) ? (bool)$resp[0] : null;
    }
}
