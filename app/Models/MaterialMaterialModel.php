<?php declare(strict_types = 1);

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

    public function getRelated(int $id) : array
    {
        $left = $this->select($this->allowedFields[0] . ' as material_id')
                    ->where($this->allowedFields[1], $id)
                    ->where($this->allowedFields[0] . ' !=', $id)
                    ->findAll();
        $right = $this->select($this->allowedFields[1] . ' as material_id')
                    ->where($this->allowedFields[0], $id)
                    ->where($this->allowedFields[1] . ' !=', $id)
                    ->findAll();
        return array_merge($left, $right);
    }

    /**
     * Automatically decides whether to delete or insert a new relationship
     * between two materials.
     *
     * @param Material $material material to insert/delete with
     */
    public function saveMaterial(Material $material) : bool
    {
        $relations = [];
        foreach ($this->getRelated($material->id) as $r) {
            $relations[] = $r->id;
        }

        $toDelete = array_filter($relations, function($r) use ($material) {
            return $r && !in_array($r, $material->related);
        });

        $toCreate = array_filter($material->related, function($r) use ($relations) {
            return $r && !in_array($r, $relations);
        });

        $this->db->transStart();
        foreach ($toDelete as $id) {
            $this->orGroupStart()
                    ->where($this->allowedFields[0], $material->id)
                    ->where($this->allowedFields[1], $id)
                ->groupEnd()
                ->orGroupStart()
                    ->where($this->allowedFields[0], $id)
                    ->where($this->allowedFields[1], $material->id)
                ->groupEnd()
                ->delete();
        }

        foreach ($toCreate as $id) {
            $this->insert([
                $this->allowedFields[0] => $material->id,
                $this->allowedFields[1] => $id,
            ]);
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
