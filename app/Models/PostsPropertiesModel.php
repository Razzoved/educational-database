<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Property;

class PostsPropertiesModel extends Model
{
    protected $table = 'posts_properties';
    protected $allowedFields = ['post_id', 'property_id'];

    private $properties = 'properties';

    /**
     * Looks for ALL properties belonging to a given post. Returns
     * an array of Property class objects ordered by tag & value.
     *
     * @param int $id posts' id whose tags we want to get
     */
    public function findProperties(int $id) : array
    {
        return $this->select("$this->properties.*")
                    ->join($this->properties, "$this->table.property_id = properties.property_id")
                    ->where("$this->table.post_id", $id)
                    ->orderBy("$this->properties.property_tag")
                    ->orderBy("$this->properties.property_value")
                    ->distinct()
                    ->get()
                    ->getCustomResultObject(Property::class);
    }

    /**
     * Returns all properties that are currently in use. Discards all
     * duplicates, returend values are ordered firt by tag, then by value.
     *
     * @return array all used properties in an array with [keys = tags]
     *               and [values = all property values of the tag]
     */
    public function getUsedProperties() : array
    {
        $query = $this->select("$this->properties.*")
                       ->join($this->properties, "$this->table.property_id = $this->properties.property_id")
                       ->orderBy("$this->properties.property_tag")
                       ->orderBy("$this->properties.property_value")
                       ->distinct()
                       ->get();
        $result = array();
        foreach ($query->getCustomResultObject(Property::class) as $property) {
            $result[$property->property_tag][] = $property->property_value;
        }
        return $result;
    }

    /**
     * Compiles an SQL query, which returns all id's of posts, whose
     * proterties meet the filtering criteria. This query is not exectured
     * and is returned as string.
     *
     * @param array $filters selected filters grouped by tags
     */
    public function getCompiledFilter(array $filters) : string
    {
        $requiredTags = 0;

        // build basic query from two tables
        $builder = $this->builder()
                        ->select("$this->table.post_id")
                        ->join($this->properties, "$this->table.property_id = $this->properties.property_id");

        // if any filters are active, apply them
        if ($filters != []) {
            foreach ($filters as $k => $v) {
                $v = array_keys($v);
                $requiredTags += count($v);
                $builder->orGroupStart()
                        ->where("$this->properties.property_tag", $k)
                        ->whereIn("$this->properties.property_value", $v)
                        ->groupEnd();
            }
        }

        // check if it has all require tags
        $builder->groupBy("$this->table.post_id")
                ->having("COUNT(*) >=", $requiredTags);

        return $builder->getCompiledSelect();
    }
}
