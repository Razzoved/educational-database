<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds a new column to properties. This column adds an optional description
 * to each property.
 * 
 * Changes here do not reflect model and entities, which have to be changed
 * manually.
 * 
 * @author Jan Martinek
 */
class PropertiesDescription extends Migration
{
    public function up()
    {
        $this->forge->addColumn('properties', [
            'property_description' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'default'    => '',
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('properties', 'property_description');
    }
}
