<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\Property;
use CodeIgniter\Model;

class PostsPropertiesModel extends Model
{
    protected $table = 'posts_properties';
    protected $allowedFields = ['post_id', 'property_id'];

    private $properties = 'properties';

    /**
     * Looks for ALL properties belonging to a given post. Returns
     * them in an array of Property class objects.
     *
     * @param int $id posts' id whose tags we want to get
     */
    public function findTags(int $id) : array
    {
        return $this->asObject('Property')
                    ->select("$this->properties.*")
                    ->join($this->properties, "$this->table.property_id = $this->properties.property_id")
                    ->where("$this->table.post_id", $id, true)
                    ->get()
                    ->getResult();
    }

    public function getCompiledAll() : string
    {
        return $this->builder()
                    ->join('properties', "$this->table.property_id = properties.property_id")
                    ->getCompiledSelect();
    }

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
                $requiredTags++;
                $v = array_keys($v);
                $builder->orGroupStart()
                        ->where("$this->properties.property_tag", $k)
                        ->whereIn("$this->properties.property_value", $v)
                        ->groupEnd();
            }
            // check if it has all require tags
            $builder->groupBy("$this->table.post_id")
                    ->having("COUNT(*) >=", $requiredTags);
        }

        return $builder->getCompiledSelect();
    }
}
