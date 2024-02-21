<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use App\Models as M;
use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Seeder;
use Exception;

class Initialize extends Seeder
{
    /**
     * Database must already be created for this command to work,
     * you can use 'php spark db:create <name>'
     */
    public function run()
    {
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
            'material_id' => $this->id(),
            'material_status' => [
                'type'       => 'ENUM',
                'constraint' => ['Published', 'Pending review', 'Draft'],
                'default'    => 'Draft',
            ],
            'material_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'material_content' => [
                'type'       => 'BLOB',
                'null'       => true,
                'default'    => null,
            ],
            'material_views' => [
                'type'       => 'INT',
                'constraint' => 10,
            ],
            'material_rating' => [
                'type'       => 'FLOAT',
                'constraint' => 10,
            ],
            'material_rating_count' => [
                'type'       => 'INT',
                'constraint' => 10,
            ],
            'published_at' => $this->date(true),
            'updated_at' => $this->date(),
        ]);
        $this->user_id('material_blame', true);
        $this->forge->addKey('material_id', true);
        $this->forge->createTable($name, true);
    }

    private function _material_material()
    {
        // TODO: consider changing this to a different model
        // INFO: new model prepared for future updates
        //       see https://app.quickdatabasediagrams.com/#/d/bkkApv
        $name = model(M\MaterialMaterialModel::class)->builder()->getTable();
        $this->forge->addField(['id' => $this->id()]);
        $this->forge->addKey('id', true);
        $this->material_id('material_id_left');
        $this->material_id('material_id_right');
        $this->forge->createTable($name, true);
    }

    /**
     * Depends on: users
     */
    private function _properties()
    {
        $name = model(M\PropertyModel::class)->builder()->getTable();
        $this->forge->addField([
            'property_id' => $this->id(),
            'property_tag' => $this->id(false, true),
            'property_value' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'property_priority' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
            ],
            'property_description' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ]
        ]);
        $this->forge->addKey('property_id', true);
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
            'user_id' => $this->id(),
            'user_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'user_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'user_password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => $this->date(),
            'updated_at' => $this->date(),
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->createTable($name, true);

        try {
            $defaults = [
                [
                    'user_name' => 'admin',
                    'user_email' => 'admin@localhost',
                    'user_password' => password_hash('admin', PASSWORD_DEFAULT)
                ],
            ];
            $this->db->table('users')->insertBatch($defaults);
        } catch (Exception $e) {
            $this->forge->dropTable('users');
            $this->forge->dropDatabase($this->db->getDatabase());
            throw $e;
        }
    }

    private function _config()
    {
        $name = model(M\ConfigModel::class)->builder()->getTable();
        $this->forge->addField([
            'config_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
            ],
            'config_value' => [
                'type'       => 'VARCHAR',
                'constraint' => 2048,
                'default'    => '',
            ],
        ]);
        $this->forge->addKey('config_id', true);
        $this->forge->createTable($name, true);

        try {
            $defaults = [
                [
                    'config_id' => 'default_image',
                    'config_value' => ASSET_PREFIX . 'default_image.png'
                ],
                [
                    'config_id' => 'about_url',
                    'config_value' => 'http://localhost/'
                ],
                [
                    'config_id' => 'home_url',
                    'config_value' => 'http://localhost/'
                ],
            ];
            $this->db->table('config')->insertBatch($defaults);
        } catch (Exception $e) {
            $this->forge->dropTable('config');
            $this->forge->dropDatabase($this->db->getDatabase());
            throw $e;
        }
    }

    /**
     * Depends on: materials
     */
    private function _views()
    {
        $name = model(M\ViewsModel::class)->builder()->getTable();
        $this->forge->addField([
            'id' => $this->id(),
            'material_views' => [
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
            'rating_uid' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'rating_value' => [
                'type'       => 'INT',
                'constraint' => 10,
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
            'resource_id' => $this->id(),
            'resource_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 2048,
            ],
            'resource_type' => [
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
        $this->forge->addKey('resource_id', true);
        $this->forge->createTable($name, true);
    }

    /** -----------------------------------------------------------------
     *                            HELPERS
     * ----------------------------------------------------------------- */

    private function material_id(string $key = 'material_id', bool $null = false)
    {
        $name = model(M\MaterialModel::class)->builder()->getTable();
        $this->forge->addField([
            $key => $this->id(false, $null),
        ]);
        $this->forge->addForeignKey($key, $name, 'material_id', 'CASCADE', 'CASCADE');
    }

    private function property_id(string $key = 'property_id', bool $null = false)
    {
        $name = model(M\PropertyModel::class)->builder()->getTable();
        $this->forge->addField([
            $key => $this->id(false, $null),
        ]);
        $this->forge->addForeignKey($key, $name, 'property_id', 'CASCADE', 'CASCADE');
    }

    private function user_id(string $key = 'user_id', bool $null = false)
    {
        $name = model(M\UserModel::class)->builder()->getTable();
        $this->forge->addField([
            $key => $this->id(false, $null),
        ]);
        $this->forge->addForeignKey($key, $name, 'user_id', 'CASCADE', 'SET NULL');
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
