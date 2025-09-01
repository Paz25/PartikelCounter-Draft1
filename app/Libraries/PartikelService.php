<?php
namespace App\Libraries;

use App\Libraries\PartikelReader;
use App\Models\PartikelCounterBufferModel;

class PartikelService
{
    public function runLoop(int $isoClass = 7)
    {
        $reader = new PartikelReader();
        $model = new PartikelCounterBufferModel();

        while (true) {
            $isOn = $reader->cekCoilStatus(1, 0x001E);

            if (!$isOn) {
                sleep(5);
                continue;
            }

            $regs = $reader->bacaSensor();
            if ($regs) {
                $values = $reader->parseData($regs);
                $evaluated = $reader->evaluasiISO($values, $isoClass);
                $model->insert($evaluated);
            }

            sleep(10);
        }
    }
}
