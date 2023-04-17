<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\Property;
use CodeIgniter\Model;

/**
 * Model that handles all operations on properties.
 * Those operations include connections to materials.
 *
 * @author Jan Martinek
 */
class PropertyModel extends Model
{
    protected $table         = 'properties';
    protected $primaryKey    = 'property_id';
    protected $allowedFields = [
        'property_tag',
        'property_value',
        'property_priority',
        'property_description',
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    // heavy operation, use only if intentionall
    protected $allowCallbacks = false;
    protected $afterFind = [
        'loadChildren',
    ];

    protected $returnType = Property::class;

    /** ----------------------------------------------------------------------
     *                           PUBLIC METHODS
     *  ------------------------------------------------------------------- */

    public function get(int $id, array $data = []) : ?Property
    {
        $item = $this->setupQuery($data)->find($id);
        if ($data['usage'] ?? false) {
            $item->usage = $this->_getUsage($item);
        }
        return $item;
    }

    public function getArray(array $data = [], int $limit = 0) : array
    {
        return $this->getUsage(
            $this->setupQuery($data)->findAll($limit),
            $data['usage'] ?? false,
        );
    }

    public function getPage(int $page = 1, array $data = [], int $perPage = 20) : array
    {
        return $this->getUsage(
            $this->setupQuery($data)->paginate($perPage, 'default', $page),
            $data['usage'] ?? false,
        );
    }

    public function getByBoth(string $tag, string $value) : ?Property
    {
        return $this->where('property_tag', $tag)
                    ->where('property_value', $value)
                    ->first();
    }

    public function getUnique(string $column = "value") : array
    {
        $column = 'property_' . $column;
        if (!in_array($column, $this->allowedFields)) {
            return [];
        }
        return $this->select($column)
                    ->orderBy($column)
                    ->distinct()
                    ->findAll();
    }

    public function getDuplicates(string $column = "value") : array
    {
        $column = 'property_' . $column;
        if (!in_array($column, $this->allowedFields)) {
            return [];
        }
        return $this->select($column)
                    ->orderBy($column)
                    ->groupBy($column)
                    ->having('COUNT(*) >', 1)
                    ->findAll();
    }

    /** ----------------------------------------------------------------------
     *                        UNIFIED QUERY SETUP
     *  ------------------------------------------------------------------- */

    protected function setupQuery(array $data = []) : PropertyModel
    {
        if (isset($data['callbacks']) && $data['callbacks'] === true) {
            $this->allowCallbacks(true);
        }
        return $this
            ->setupSort($data['sort'] ?? "", $data['sortDir'] ?? "")
            ->setupFilters($data['filters'] ?? [])
            ->setupSearch($data['search'] ?? "");
    }

    protected function setupSort(string $sort, string $sortDir)
    {
        if (
            $sort !== $this->createdField &&
            $sort !== $this->updatedField &&
            $sort !== $this->primaryKey
        ) {
            $sort = 'property_' . $sort;
            $sort = in_array($sort, $this->allowedFields) || $sort === $this->primaryKey ? $sort : "";
        }

        if ($sort !== "") {
            $sort = $this->primaryKey;
        }

        if ($sort !== 'property_priority') {
            $this->orderBy('property_priority', 'desc');
        }

        $this->orderBy($sort, strtolower($sortDir) === 'desc' ? 'DESC' : 'ASC');

        if ($sort !== 'property_tag') {
            $this->orderBy('property_tag');
        }

        if ($sort !== 'property_value') {
            $this->orderBy('property_value');
        }

        return $this;
    }

    protected function setupFilters(array $filters)
    {
        if ($filters !== []) {
            if (isset($filters['Tags'])) {
                $this->whereIn('property_tag', array_keys($filters['Tags']));
            }

            if (isset($filters['Values'])) {
                $this->groupStart();
                foreach ($filters['Values'] as $value => $state) {
                    $this->orLike('property_value', $value, 'after');
                }
                $this->groupEnd();
            }
            if (isset($filters['tag'])) {
                $this->where('property_tag', $filters['tag']);
            }
            if (isset($filters['value'])) {
                $this->where('property_value', $filters['value']);
            }
        }
        return $this;
    }

    protected function setupSearch(string $search)
    {
        if ($search === "") {
            return $this;
        }
        return $this->orLike('property_tag', $search, 'both', true, true)
                    ->orLike('property_value', $search, 'both', true, true);
    }

    /** ----------------------------------------------------------------------
     *                              CALLBACKS
     *  ------------------------------------------------------------------- */

    protected function loadChildren(array $data)
    {
        if (!isset($data['data'])) {
            return $data;
        }

        if ($data['method'] === 'find') {
            $data['data']->children = $this->_loadChildren($data['data']);
        } else foreach ($data['data'] as $property) {
            $property->children = $this->_loadChildren($property);
        }

        return $data;
    }

    // protected function getCategory(array $data)
    // {
    //     if (!isset($data['data'])) {
    //         return $data;
    //     }

    //     if ($data['method'] === 'find') {
    //         $data['data']->tag = $this->_getCategory($data['data']);
    //     } else foreach ($data['data'] as $property) {
    //         $property->tag = $this->_getCategory($property);
    //     }

    //     return $data;
    // }

    protected function _loadChildren(Property $property) : array
    {
        return $this->allowCallbacks(true)
                    ->where('property_tag', $property->id)
                    ->getArray();
    }

    // protected function _getCategory(Property $property) : ?string
    // {
    //     $tag = $property->property_tag !== 0
    //         ? $this->allowCallbacks(false)->find($property->property_tag)
    //         : null;
    //     return $tag->value ?? null;
    // }

    /** ----------------------------------------------------------------------
     *                              HELPERS
     *  ------------------------------------------------------------------- */

    protected function getUsage(array $items, bool $doUsage) : array
    {
        if ($doUsage === true) {
            foreach ($items as $item) {
                $item->usage = $this->_getUsage($item);
            }
        }
        return $items;
    }

    protected function _getUsage(Property $item) : int
    {
        return count(
            model(MaterialPropertyModel::class)
                ->allowCallbacks(false)
                ->where($this->primaryKey, $item->id)
                ->findAll()
        );
    }
}
