<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPrimaryKeyMatMat extends Migration
{
    public function up()
    {
        $this->forge->addColumn('material_material', [
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'first'          => true,
                'null'           => false,
                'auto_increment' => true,
                'unique'         => true // required for auto_increment
            ],
        ]);
        $this->forge->addKey('id', true, true)
                    ->processIndexes('material_material');

        // drops the unique index (already taken care of by PRIMARY KEY)
        $this->forge->dropKey('material_material', 'id');
    }

    public function down()
    {
        $this->forge->dropColumn('material_material', 'id');
    }
}
