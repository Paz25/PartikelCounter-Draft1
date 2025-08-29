<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class IsoLimitsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'iso_class' => 1,
                'Limit03' => 10,
                'Limit05' => null,
                'Limit10' => null,
                'Limit25' => null,
                'Limit50' => null,
                'Limit100' => null,
            ],
            [
                'iso_class' => 2,
                'Limit03' => 100,
                'Limit05' => 4,
                'Limit10' => null,
                'Limit25' => null,
                'Limit50' => null,
                'Limit100' => null,
            ],
            [
                'iso_class' => 3,
                'Limit03' => 1000,
                'Limit05' => 35,
                'Limit10' => 8,
                'Limit25' => null,
                'Limit50' => null,
                'Limit100' => null,
            ],
            [
                'iso_class' => 4,
                'Limit03' => 10000,
                'Limit05' => 352,
                'Limit10' => 83,
                'Limit25' => null,
                'Limit50' => null,
                'Limit100' => null,
            ],
            [
                'iso_class' => 5,
                'Limit03' => 100000,
                'Limit05' => 3520,
                'Limit10' => 832,
                'Limit25' => null,
                'Limit50' => 29,
                'Limit100' => null,
            ],
            [
                'iso_class' => 6,
                'Limit03' => null,
                'Limit05' => 35200,
                'Limit10' => 8320,
                'Limit25' => null,
                'Limit50' => 293,
                'Limit100' => null,
            ],
            [
                'iso_class' => 7,
                'Limit03' => null,
                'Limit05' => 352000,
                'Limit10' => 83200,
                'Limit25' => null,
                'Limit50' => 2930,
                'Limit100' => null,
            ],
            [
                'iso_class' => 8,
                'Limit03' => null,
                'Limit05' => 3520000,
                'Limit10' => 832000,
                'Limit25' => null,
                'Limit50' => 29300,
                'Limit100' => null,
            ],
            [
                'iso_class' => 9,
                'Limit03' => null,
                'Limit05' => 35200000,
                'Limit10' => 8320000,
                'Limit25' => null,
                'Limit50' => 293000,
                'Limit100' => null,
            ],
        ];

        $this->db->table('iso_limits')->insertBatch($data);
    }
}