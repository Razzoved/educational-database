<?php

namespace App\Database\Migrations;

use App\Models\PropertyModel;
use CodeIgniter\Database\Migration;


/**
 * Changes the type of property_tag to same as id.
 * Expects the data to be transformed into ids already.
 *
 * @author Jan Martinek
 */
class ChangeProperties extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('properties', [
            'property_tag' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
                'default'  => 0,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('properties', [
            'property_tag' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'     => false,
                'default'    => '',
            ]
        ]);
    }
}