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
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'material_id',
        'property_id'
    ];
    protected $useAutoIncrement = true;
    // protected $allowCallbacks = true;
    // protected $afterFind = [
    //     'getProperty'
    // ];
    // protected $returnType = Property::class;

    /** ----------------------------------------------------------------------
     *                           PUBLIC METHODS
     *  ------------------------------------------------------------------- */

    public function get(int $materialId) : array
    {
        $properties = model(PropertyModel::class)->getTree();

        $this->select('property_id')
             ->groupBy('property_id')
             ->having('COUNT(material_id) > 0')
             ->where('material_id', $materialId);

        $this->recursiveFilter(
            $properties,
            array_column($this->findAll(), 'property_id')
        );

        return $properties->children;
    }

    public function getArray(int $materialId) : array
    {
        return model(PropertyModel::class)
            ->join($this->table, 'property_id')
            ->where('material_id', $materialId)
            ->getArray();
    }

    public function getUsed() : array
    {
        $properties = model(PropertyModel::class)->getTree();

        $this->select('property_id')
             ->groupBy('property_id')
             ->having('COUNT(material_id) >', 0);

        if (!session('isLoggedIn')) {
            $this->join('materials', 'material_id')
                 ->where('material_status', StatusCast::PUBLIC);
        }

        $this->recursiveFilter(
            $properties,
            array_column($this->findAll(), 'property_id')
        );

        return $properties->children;
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
                        ->whereIn('properties.property_id', $v)
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
        $oldProperties = $this->getArray($material->id);

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
     *                              HELPERS
     *  ------------------------------------------------------------------- */

    protected function recursiveFilter(Property &$source, array $valid) : bool
    {
        $children = $source->children;
        foreach ($children as $k => $child) {
            if (!$this->recursiveFilter($child, $valid)) {
                unset($children[$k]);
            }
        }

        $source->__set('children', $children);

        return count($source->children) > 0
            || in_array($source->id, $valid);
    }
}
