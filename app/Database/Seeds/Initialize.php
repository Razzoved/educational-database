<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use App\Models as M;
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
        $this->_config();
        $this->_users();
        $this->_materials();
        $this->_material_material();
        $this->_properties();
        $this->_material_property();
        $this->_views();
        $this->_ratings();
        $this->_resources();
    }

    private function _materials()
    {
        $name = model(M\MaterialModel::class)->builder()->getTable();
        $this->forge->addField([
            'id' => $this->id(),
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['published', 'draft'],
                'default'    => 'draft',
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'content' => [
                'type'       => 'BLOB',
                'null'       => true,
                'default'    => null,
            ],
            'views' => [
                'type'       => 'INT',
                'constraint' => 10,
            ],
            'rating' => [
                'type'       => 'FLOAT',
                'constraint' => 10,
            ],
            'rating_count' => [
                'type'       => 'INT',
                'constraint' => 10,
            ],
            'published_at' => $this->date(),
            'updated_at' => $this->date(),
        ]);
        $this->user_id();
        $this->forge->addKey('id', true);
        $this->forge->createTable($name, true);
    }

    private function _material_material()
    {
        // TODO: consider changing this to a different model
        $name = model(M\MaterialMaterialModel::class)->builder()->getTable();
        $matName = model(M\MaterialModel::class)->builder()->getTable();
        $this->forge->addField([
            'id'                => $this->id(),
            'material_id_left'  => $this->id(false),
            'material_id_right' => $this->id(false),
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('material_id_left', $matName, 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('material_id_right', $matName, 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable($name, true);
    }

    /**
     * Depends on: users
     */
    private function _properties()
    {
        $name = model(M\PropertyModel::class)->builder()->getTable();
        $this->forge->addField([
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
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('parent', $name, 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable($name, true);
    }

    /**
     * Depends on: materials, properties
     */
    private function _material_property()
    {
        $name = model(M\MaterialPropertyModel::class)->builder()->getTable();
        $this->forge->addField([
            'id' => $this->id(),
        ]);
        $this->material_id();
        $this->property_id();
        $this->forge->addKey('id', true);
        $this->forge->createTable($name, true);
    }

    private function _users()
    {
        $name = model(M\UserModel::class)->builder()->getTable();
        $this->forge->addField([
            'id' => $this->id(),
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => $this->date(),
            'updated_at' => $this->date(),
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable($name, true);
    }

    private function _config()
    {
        $name = model(M\ConfigModel::class)->builder()->getTable();
        $this->forge->addField([
            'id' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
            ],
            'value' => [
                'type'       => 'VARCHAR',
                'constraint' => 2048,
                'default'    => '',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable($name, true);
    }

    /**
     * Depends on: materials
     */
    private function _views()
    {
        $name = model(M\ViewsModel::class)->builder()->getTable();
        $this->forge->addField([
            'id' => $this->id(),
            'views' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
            ],
            'created_at' => $this->date(),
        ]);
        $this->material_id();
        $this->forge->addKey('id', true);
        $this->forge->createTable($name, true);
    }

    /**
     * Depends on: materials
     */
    private function _ratings()
    {
        $name = model(M\RatingsModel::class)->builder()->getTable();
        $this->forge->addField([
            'id' => $this->id(),
            'user' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'value' => [
                'type'       => 'INT',
                'constraint' => 10,
                'null'       => true,
                'default'    => null,
            ],
        ]);
        $this->material_id();
        $this->forge->addKey('id', true);
        $this->forge->createTable($name, true);
    }

    /**
     * Depends on: materials
     */
    private function _resources()
    {
        $name = model(M\ResourceModel::class)->builder()->getTable();
        $this->forge->addField([
            'id' => $this->id(),
            'path' => [
                'type'       => 'VARCHAR',
                'constraint' => 2048,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
            'created_at' => $this->date(),
            'updated_at' => $this->date(),
            'deleted_at' => $this->date(true),
        ]);
        $this->material_id();
        $this->forge->addKey('id', true);
        $this->forge->createTable($name, true);
    }

    /** -----------------------------------------------------------------
     *                            HELPERS
     * ----------------------------------------------------------------- */

    private function material_id()
    {
        $name = model(M\MaterialModel::class)->builder()->getTable();
        $this->forge->addField([
            'material_id' => $this->id(false),
        ]);
        $this->forge->addForeignKey('material_id', $name, 'id', 'CASCADE', 'CASCADE');
    }

    private function property_id()
    {
        $name = model(M\PropertyModel::class)->builder()->getTable();
        $this->forge->addField([
            'property_id' => $this->id(false),
        ]);
        $this->forge->addForeignKey('property_id', $name, 'id', 'CASCADE', 'CASCADE');
    }

    private function user_id()
    {
        $name = model(M\UserModel::class)->builder()->getTable();
        $this->forge->addField([
            'user_id' => $this->id(false),
        ]);
        $this->forge->addForeignKey('user_id', $name, 'id', 'CASCADE', 'SET NULL');
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

    private static function date(bool $nullable = false)
    {
        return [
            'type'    => 'TIMESTAMP',
            'default' => new RawSql('CURRENT_TIMESTAMP'),
            'null'    => $nullable,
        ];
    }
}
