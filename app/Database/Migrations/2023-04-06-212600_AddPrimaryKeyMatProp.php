<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds id as a primary key to  material_property table.
 * This is done for better compatibility with CodeIgniter.
 *
 * @author Jan Martinek
 */
class AddPrimaryKeyMatProp extends Migration
{
    public function up()
    {
        $this->db->query(Helper::getAddIdQuery($this->db->DBPrefix . 'material_property'));
    }

    public function down()
    {
        $this->forge->dropColumn('material_property', 'id');
    }
}
