<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Exception;

/**
 * Removes material_author column from material table. This migration
 * cannot be rollbacked, since it will lose all relevant data in the process
 * (ie. only the column will be added, no data into it).
 *
 * Changes here do not reflect model and entities, which have to be changed
 * manually.
 *
 * @author Jan Martinek
 */
class MaterialRemoveAuthor extends Migration
{
    public function up()
    {
        $data = array_map(function($item) {
                $item = $item->toRawArray();
                return [
                    'id' => $item['material_id'] ?? 0,
                    'author' => $item['material_author'] ?? '',
                ];
            },
            model(MaterialModel::class)->findAll()
        );
        file_put_contents(WRITEPATH . 'material-authors.txt', json_encode($data));
        $this->forge->dropColumn('materials', 'material_author');
    }

    public function down()
    {
        $this->forge->addColumn('materials', [
            'material_author' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'default'    => '',
            ]
        ]);
        try {
            $data = file_get_contents(WRITEPATH . 'material-authors.txt');
            $data = $data === false ? [] : json_decode($data);
            $model = model(MaterialModel::class);
            if (!$model) {
                throw new Exception('no model found');
            }
            foreach ($data as $item) {
                $this->db->table('materials')->update(
                    ['material_author' => $item->author],
                    ['material_id' => $item->id]
                );
            }
        } catch (Exception $e) {
            $this->forge->dropColumn('materials', 'material_author');
            throw $e;
        }
    }
}
