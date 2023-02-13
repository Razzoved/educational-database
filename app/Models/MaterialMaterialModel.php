<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\Cast\StatusCast;
use App\Entities\Material;
use CodeIgniter\Model;
use CodeIgniter\Database\BaseConnection;

class MaterialMaterialModel extends Model
{
    protected $table = 'material_material';
    protected $primaryKey = 'material_id_left';
    protected $allowedFields = ['material_id_left', 'material_id_right'];

    /**
     * Looks for ALL pairs of materials where at least one member has
     * the given id. Returns an array of such materials.
     *
     * @param int $id id of material whose tags we want to get
     */
    public function getRelated(int $id, bool $onlyVisible = true) : array
    {
        $ids = $this->builder()
                    ->orWhere('material_id_left', $id)
                    ->orWhere('material_id_right', $id)
                    ->get()
                    ->getResultArray();

        echo json_encode($ids);

        // $result = array();
        // foreach ($ids as $key => $value) {
        //     $result[] = model(MaterialModel::class)->find($value);
        // }
        // return $result;

        return [];
    }

    /**
     * Automatically decides whether to delete or insert a new property
     * to the material.
     *
     * @param material $material material to insert/delete with
     * @param BaseConnection $db database connection
     */
    public function handleUpdate(Material $material, array $newRelations, BaseConnection $db = null) : void
    {
        if (!isset($db)) $db = $this->db;

        $relations = $this->getRelated($material->id);

        $toDelete = array_filter($relations, function($r) use ($newRelations) {
            return $r && !in_array($r, $newRelations);
        });

        $toCreate = array_filter($newRelations, function($r) use ($relations) {
            return $r && !in_array($r, $relations);
        });

        foreach ($toDelete as $p) {
            $db->table($this->table)
               ->where('material_id', $material->id)
               ->where('property_id', $p->id)
               ->delete();
        }

        foreach ($toCreate as $p) {
            $db->table($this->table)->insert([
                'material_id' => $material->id,
                'property_id' => $p->id,
            ]);
        }
    }
}
