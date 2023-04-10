<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds a new column to properties. This column acts as a priority filter
 * for each category/property.
 *
 * Higher value means higher priority. All rows affected by this migration
 * are set to 0 by default. It is up to administrators to override these.
 *
 * Values can be negative, in which case their priority will be lower than
 * default. However it is not advised to use this option.
 *
 * @author Jan Martinek
 */
class PropertiesPriority extends Migration
{
    public function up()
    {
        $this->forge->addColumn('properties', [
            'property_priority' => [
                'type'     => 'INT',
                'null'     => false,
                'default'  => 0,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('properties', 'property_priority');
    }
}
