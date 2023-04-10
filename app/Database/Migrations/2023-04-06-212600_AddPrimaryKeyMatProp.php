<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

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
