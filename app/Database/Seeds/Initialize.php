<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Seeder;

class Initialize extends Seeder
{
    public function run()
    {
        $dbPrefix = $this->db->getPrefix();
        $dbName = $this->db->getDatabase() == '' ? 'materials' : $this->db->getDatabase();

        // create db if it does not exist yet
        $this->forge->createDatabase($dbName, true);

        // create all tables, order is set because of dependencies
        $this->_materials();
        $this->_material_material();
        $this->_properties();
        $this->_users();
        $this->_config();
        $this->_views();
        $this->_ratings();
        $this->_resources();
    }

    private function _materials()
    {
    }

    private function _material_material()
    {
    }

    private function _properties()
    {
        $f = $this->forge;

        $f->addField([
            'id' => $this->id(),
            'parent' => $this->id(false, true),
            'value' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'priority' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ]
        ]);

        $f->addKey('id', true);
        $f->addForeignKey('parent', 'properties', 'id', 'CASCADE', 'CASCADE');

        $f->createTable('properties', true);
        $f->reset();
    }

    private function _users()
    {
        $f = $this->forge;

        $f->addField([
            'id' => $this->id(),
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'unique'     => true,
                'constraint' => 100,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => $this->date(),
            'updated_at' => $this->date(),
        ]);

        $f->addKey('id', true);

        $f->createTable('users', true);
        $f->reset();
    }

    private function _config()
    {
    }

    private function _views()
    {
    }

    private function _ratings()
    {
    }

    private function _resources()
    {
    }

    private static function id(bool $increment = true, bool $nullable = false)
    {
        return [
            'type'           => 'BIGINT',
            'constraint'     => 20,
            'unsigned'       => true,
            'auto_increment' => $increment,
            'null'           => !$increment && $nullable,
        ];
    }

    private static function date()
    {
        return [
            'type'    => 'TIMESTAMP',
            'default' => new RawSql('CURRENT_TIMESTAMP'),
        ];
    }
}
