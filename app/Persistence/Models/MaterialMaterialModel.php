<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\Material;
use CodeIgniter\Model;

/**
 * This model handles the relations between materials. Amount of relations
 * per material is not limited. Intended to be used a a link between same
 * material with different language, or with general similarity of topics.
 *
 * @author Jan Martinek
 */
class MaterialMaterialModel extends Model
{
    protected $table = 'material_material';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'material_id_left',
        'material_id_right'
    ];
    protected $useAutoIncrement = true;
    protected $allowCallbacks = true;
    protected $afterFind = [
        'loadData',
        'loadThumbnail',
    ];
    protected $returnType = Material::class;

    /** ----------------------------------------------------------------------
     *                           PUBLIC METHODS
     *  ------------------------------------------------------------------- */

    /**
     * Returns all relations as an array of Material objects.
     * Each material has a thumbnail and data loaded.
     *
     * @param int $id The id of the material to find relations to.
     */
    public function getRelated(int $id): array
    {
        $left = $this->builder()
            ->select("{$this->allowedFields[0]} as id")
            ->where($this->allowedFields[1], $id);
        $right = $this->builder()
            ->select("{$this->allowedFields[1]} as id")
            ->where($this->allowedFields[0], $id);
        return $left->union($right)->get()->getResult(Material::class);
    }

    /**
     * Automatically decides whether to delete or insert a new relationship
     * between two materials.
     *
     * @param Material $material material to insert/delete with
     */
    public function saveMaterial(Material $material): bool
    {
        $saved = $this->allowCallbacks(false)->getRelated($material->id);

        $cmp = fn($a, $b) => $a->id === $b->id;
        $material->related = $material->related ?? [];
        
        $this->db->transStart();

        foreach (array_udiff($material->related, $saved, $cmp) as $relation) {
            $this->insert([
                $this->allowedFields[0] => $material->id,
                $this->allowedFields[1] => $relation->id,
            ]);
        }

        foreach (array_udiff($saved, $material->related, $cmp) as $relation) {
            $this->orWhere([
                $this->allowedFields[0] => $relation->id,
                $this->allowedFields[1] => $relation->id,
            ])->delete();
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /** ----------------------------------------------------------------------
     *                              CALLBACKS
     *  ------------------------------------------------------------------- */

    protected function loadData(array $data)
    {
        if (!isset($data['data'])) {
            return $data;
        }

        $model = model(MaterialModel::class);

        if ($data['method'] === 'find') {
            $data['data'] = $model->allowCallbacks(false)->find($data['data']->id);
        } else foreach ($data['data'] as $k => $material) {
            if ($material) {
                $model = $model->allowCallbacks(false);
                $data['data'][$k] = $model->find($material->id);
            }
        }

        return $data;
    }

    protected function loadThumbnail(array $data)
    {
        if (!isset($data['data'])) {
            return $data;
        }

        $model = model(ResourceModel::class);

        if ($data['method'] === 'find') {
            $data['data'] = $model->getThumbnail($data['data']->id);
        } else foreach ($data['data'] as $material) {
            if ($material) {
                $material->resources = $model->getThumbnail($material->id);
            }
        }

        return $data;
    }
}
