<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\Cast\StatusCast;
use App\Entities\Material;
use App\Entities\Property;
use CodeIgniter\Model;

/**
 * Conneting model between materials and their properties.
 * Its methods retrieve properties from property model.
 *
 * @author Jan Martinek
 */
class MaterialPropertyModel extends Model
{
    protected $table = 'material_property';
    protected $primaryKey = 'material_id';
    protected $allowedFields = ['material_id', 'property_id'];

    protected $afterFind = ['getProperty'];

    protected $returnType = Property::class;

    /** ----------------------------------------------------------------------
     *                           PUBLIC METHODS
     *  ------------------------------------------------------------------- */

    public function get(int $materialId) : array
    {
        return $this->where("$this->table.material_id", $materialId)
                    ->findAll();
    }

    public function getUsed() : array
    {
        $this->allowCallbacks(false)
             ->join('properties', 'property_id')
             ->select('property_tag, property_value')
             ->groupBy('property_tag, property_value')
             ->orderBy('property_tag, property_value')
             ->having('COUNT(material_id) >', 0);

        if (!session('isLoggedIn')) {
            $this->join('materials', 'material_id')
                 ->where('material_status', StatusCast::PUBLIC);
        }

        foreach ($this->findAll() as $property) {
            $result[$property->property_tag][] = $property->property_value;
        }

        return $result;
    }

    /**
     * Compiles an SQL query, which returns all id's of materials, whose
     * proterties meet the filtering criteria. This query is not executed
     * and is returned as string.
     *
     * @param array $filters selected filters grouped by tags
     */
    public function getCompiledFilter(array $filters) : string
    {
        $requiredTags = 0;

        // build basic query from two tables
        $builder = $this->builder()
                        ->select("$this->table.material_id")
                        ->join('properties', "$this->table.property_id = properties.property_id");

        // if any filters are active, apply them
        if ($filters !== []) {
            foreach ($filters as $k => $v) {
                $v = array_keys($v);
                $requiredTags += count($v);
                $builder->orGroupStart()
                        ->where('properties.property_tag', $k)
                        ->whereIn('properties.property_value', $v)
                        ->groupEnd();
            }
        }

        // check if it has all require tags
        $builder->groupBy("$this->table.material_id")
                ->having('COUNT(*) >=', $requiredTags);

        return $builder->getCompiledSelect();
    }

    /**
     * Automatically decides whether to delete or insert a new property
     * to the material.
     *
     * @param material $material material to insert/delete with
     */
    public function saveMaterial(Material $material) : bool
    {
        $oldProperties = $this->get($material->id);

        $toDelete = array_filter($oldProperties, function($p) use ($material) {
            return $p && !in_array($p, $material->properties);
        });

        $toCreate = array_filter($material->properties, function($p) use ($oldProperties) {
            return $p && !in_array($p, $oldProperties);
        });

        $this->db->transStart();
        foreach ($toDelete as $p) {
            $this
               ->where('material_id', $material->id)
               ->where('property_id', $p->id)
               ->delete();
        }

        foreach ($toCreate as $p) {
            $this->insert([
                'material_id' => $material->id,
                'property_id' => $p->id,
            ]);
        }
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /** ----------------------------------------------------------------------
     *                              CALLBACKS
     *  ------------------------------------------------------------------- */

    protected function getProperty(array $data)
    {
        if (!isset($data['data'])) {
            return $data;
        }

        foreach ($data['data'] as $k => $v) {
            $data['data'][$k] = model(PropertyModel::class)->get($v->id);
        }

        return $data;
    }
}
