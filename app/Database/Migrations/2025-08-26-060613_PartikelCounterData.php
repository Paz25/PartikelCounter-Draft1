<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PartikelCounterData extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'Id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'mac_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'Tanggal DATE DEFAULT CURRENT_TIMESTAMP NOT NULL',
            'Value03' => [
                'type'       => 'VARCHAR',
                'constraint' => 9,
                'null' => true,
            ],
            'Limit03' => [
                'type'       => 'VARCHAR',
                'constraint' => 9,
                'null' => true,
            ],
            'Value05' => [
                'type'       => 'VARCHAR',
                'constraint' => 9,
                'null' => true,
            ],
            'Limit05' => [
                'type'       => 'VARCHAR',
                'constraint' => 9,
                'null' => true,
            ],
            'Value10' => [
                'type'       => 'VARCHAR',
                'constraint' => 9,
                'null' => true,
            ],
            'Limit10' => [
                'type'       => 'VARCHAR',
                'constraint' => 9,
                'null' => true,
            ],
            'Value25' => [
                'type'       => 'VARCHAR',
                'constraint' => 9,
                'null' => true,
            ],
            'Limit25' => [
                'type'       => 'VARCHAR',
                'constraint' => 9,
                'null' => true,
            ],
            'Value50' => [
                'type'       => 'VARCHAR',
                'constraint' => 9,
                'null' => true,
            ],
            'Limit50' => [
                'type'       => 'VARCHAR',
                'constraint' => 9,
                'null' => true,
            ],
            'Value100' => [
                'type'       => 'VARCHAR',
                'constraint' => 9,
                'null' => true,
            ],
            'Limit100' => [
                'type'       => 'VARCHAR',
                'constraint' => 9,
                'null' => true,
            ],
            'Status' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'port_ke' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'null' => true,
            ],
            'user' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ]
        ]);
        $this->forge->addKey('Id', true);
        $this->forge->createTable('partikel_counter_data');
    }

    public function down()
    {
        $this->forge->dropTable('partikel_counter_data');
    }
}
