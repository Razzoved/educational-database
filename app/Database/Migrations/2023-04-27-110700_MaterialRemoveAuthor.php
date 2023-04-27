<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Removes material_author column from material table. This migration
 * cannot be rollbacked, since it will lose all relevant data in the process
 * (ie. only the column will be added, no data into it).
 *
 * Changes here do not reflect model and entities, which have to be changed
 * manually.
 *
 * @author Jan Martinek
 */
class MaterialRemoveAuthor extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('materials', 'material_author');
    }

    public function down()
    {
        $this->forge->addColumn('materials', [
            'material_author' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'default'    => '',
            ]
        ]);
    }
}
