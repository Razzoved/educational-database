<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Exception;

/**
 * Creates a new table in the database. This table is meant to contain
 * configuration information settable by administrators.
 *
 * (example: default material image, about page location, etc.)
 *
 * @author Jan Martinek
 */
class AddConfig extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'config_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
            ],
            'config_value' => [
                'type'       => 'VARCHAR',
                'constraint' => 2048,
            ],
        ]);
        $this->forge->addPrimaryKey('config_id');
        $this->forge->createTable('config');

        try {
            $defaults = [
                [
                    'config_id' => 'about_url',
                    'config_value' => 'https://www.academicintegrity.eu/wp/about-enai'
                ],
                [
                    'config_id' => 'home_url',
                    'config_value' => 'https://www.academicintegrity.eu'
                ],
            ];
            $this->db->table('config')->insertBatch($defaults);
        } catch (Exception $e) {
            $this->forge->dropTable('config');
            throw $e;
        }
    }

    public function down()
    {
        $this->forge->dropTable('config');
    }
}
