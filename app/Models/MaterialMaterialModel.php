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

    /**
     * Returns all relations as an array of Material objects.
     * Each such material has an additional property loaded
     * under the key 'relation_id'.
     *
     * @param int $id The id of the material to find relations to.
     */
    public function getRelated(int $id) : array
    {
        $left = $this->select($this->allowedFields[0] . ' as material_id, ' . $this->primaryKey . ' as relation_id')
                    ->where($this->allowedFields[1], $id)
                    ->where($this->allowedFields[0] . ' !=', $id)
                    ->findAll();
        $right = $this->select($this->allowedFields[1] . ' as material_id, ' . $this->primaryKey . ' as relation_id')
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
        $relations = $this->allowCallbacks(false)->getRelated($material->id);

        $this->db->transStart();

        $newRelations = $material->related ?? [];
        array_walk($newRelations, function ($r) use ($material, $relations) {
            foreach ($relations as $k => $rel) {
                if ($r->id === $rel->id) {
                    unset($relations[$k]);
                    break;
                }
            }
            $this->insert([
                $this->allowedFields[0] => $material->id,
                $this->allowedFields[1] => $r->id,
            ]);
        });

        // echo '<pre>';
        // echo var_dump('RELATIONS: ', $relations);
        // echo '</pre>';

        foreach ($relations as $rel) {
            $this->delete($rel->relation_id);
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
