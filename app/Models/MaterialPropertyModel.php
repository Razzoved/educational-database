<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\Cast\StatusCast;
use App\Entities\Material;
use App\Entities\Property;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Model;

class MaterialPropertyModel extends Model
{
    protected $table = 'material_property';
    protected $primaryKey = 'material_id';
    protected $allowedFields = ['material_id', 'property_id'];

    /**
     * Looks for ALL properties belonging to a given material. Returns
     * an array of Property class objects ordered by tag & value.
     *
     * @param int $id id of material whose tags we want to get
     */
    public function getByMaterial(int $id) : array
    {
        return model(PropertyModel::class)->getData()->builder()
            ->select('properties.*')
            ->join("$this->table", "$this->table.property_id = properties.property_id")
            ->where("$this->table.material_id", $id)
            ->get()
            ->getCustomResultObject(Property::class);
    }

    /**
     * Returns all properties that are currently in use. Discards all
     * duplicates, returend values are ordered first by tag, then by value.
     *
     * @param bool $onlyVisible if only visible materials are taken into account
     *
     * @return array all used properties in an array with [key = tag]
     *               and [value = array of all property values of the tag]
     */
    public function getUsedProperties(bool $onlyVisible = true) : array
    {
        $show = $onlyVisible ? array(StatusCast::PUBLIC) : StatusCast::VALID_VALUES;

        $properties = model(PropertyModel::class)->getData()->builder()->getCompiledSelect();
        $builder = $this->builder()
            ->select('p.*')
            ->join("($properties) p", "$this->table.property_id = p.property_id")
            ->join("materials", "materials.material_id = $this->table.material_id")
            ->whereIn('materials.material_status', $show)
            ->distinct();

        $result = array();
        foreach ($builder->get()->getCustomResultObject(Property::class) as $property) {
            $result[$property->property_tag][] = $property->property_value;
        }

        return $result;
    }

    public function getAllProperties() : array
    {
        $result = array();
        foreach (model(PropertyModel::class)->getData()->builder()
                    ->select('properties.*')
                    ->get()
                    ->getCustomResultObject(Property::class) as $property) {
            $result[$property->property_tag][] = $property->property_value;
        }
        return $result;
    }

    /**
     * Checks if given property id is used by any material.
     *
     * @return bool true if it is used by any material, false otherwise
     */
    public function isUsed(int $id) : bool
    {
        return array_key_first($this->builder()
                                    ->select('*')
                                    ->where('property_id', $id)
                                    ->get(1)
                                    ->getResult()) !== null;
    }

    public function getPropertyUsage(int $id) : int
    {
        return array_sum(array_column(
            $this->builder()
                 ->selectCount('material_id', 'count')
                 ->where('property_id', $id)
                 ->get()
                 ->getResult(),
            'count'
        ));
    }

    /**
     * Returns a single property of given id from the database as
     * a Property object.
     *
     * @return ?Property property of given id or null
     */
    public function getPropertyWithUsage(int $id) : ?Property
    {
        return $this->builder()
                    ->select('properties.*')
                    ->selectCount('material_id', 'usage')
                    ->join('properties', "$this->table.property_id = properties.property_id", 'right')
                    ->groupBy('properties.property_id')
                    ->where('properties.property_id', $id)
                    ->get()
                    ->getCustomRowObject(0, Property::class);
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
     * @param BaseConnection $db database connection
     */
    public function handleUpdate(Material $material, BaseConnection $db = null) : void
    {
        if (!isset($db)) $db = $this->db;

        $oldProperties = $this->getByMaterial($material->id);

        $toDelete = array_filter($oldProperties, function($p) use ($material) {
            return $p && !in_array($p, $material->properties);
        });

        $toCreate = array_filter($material->properties, function($p) use ($oldProperties) {
            return $p && !in_array($p, $oldProperties);
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
