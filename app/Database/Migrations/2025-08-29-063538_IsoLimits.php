<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIsoLimits extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'iso_class' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => false,
                'unique' => true,
            ],
            'Limit03' => ['type' => 'INT', 'null' => true],
            'Limit05' => ['type' => 'INT', 'null' => true],
            'Limit10' => ['type' => 'INT', 'null' => true],
            'Limit25' => ['type' => 'INT', 'null' => true],
            'Limit50' => ['type' => 'INT', 'null' => true],
            'Limit100' => ['type' => 'INT', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('iso_limits', true);
    }

    public function down()
    {
        $this->forge->dropTable('iso_limits', true);
    }
}