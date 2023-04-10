<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPrimaryKeyMatMat extends Migration
{
    public function up()
    {
        $this->db->query(Helper::getAddIdQuery($this->db->DBPrefix . 'material_material'));
    }

    public function down()
    {
        $this->forge->dropColumn('material_material', 'id');
    }
}
