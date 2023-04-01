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
    protected $primaryKey = 'material_id_left'; // no primary key
    protected $allowedFields = [
        'material_id_left',
        'material_id_right'
    ];

    protected $afterFind = [
        'loadData',
        'loadThumbnail',
    ];

    protected $returnType = Material::class;

    /** ----------------------------------------------------------------------
     *                           PUBLIC METHODS
     *  ------------------------------------------------------------------- */

    /**
     * Looks for ALL pairs of materials where at least one member has
     * the given id. Returns an array of such materials.
     *
     * @param int $id id of material whose tags we want to get
     *
     * @return array of objects or strings
     */
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
        foreach ($data['data'] as $k => $material) {
            $data['data'][$k] = model(MaterialModel::class)->get($material->id, ['callbacks' => false]);
        }
        return $data;
    }

    protected function loadThumbnail(array $data)
    {
        if (!isset($data['data'])) {
            return $data;
            }
        foreach ($data['data'] as $material) {
            $material->resources = model(ResourceModel::class)->getThumbnail($material->id);
        }
        return $data;
    }
}
