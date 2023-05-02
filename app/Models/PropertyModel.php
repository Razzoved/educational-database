<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\Property;
use App\Libraries\Cache;
use CodeIgniter\Model;
use CodeIgniter\Validation\Exceptions\ValidationException;

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

    protected $allowCallbacks = true;
    protected $beforeFind = [
        'checkCache',
    ];
    protected $afterFind = [
        'saveCache',
    ];
    protected $afterUpdate = [
        'revalidateCache',
    ];
    protected $afterDelete = [
        'revalidateCache',
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
            ->setupCategory($data['category'] ?? false)
            ->setupFilters($data['filters'] ?? [])
            ->setupSearch($data['search'] ?? "");
    }

    protected function setupSort(string $sort, string $sortDir)
    {
        if (
            $sort !== $this->createdField &&
            $sort !== $this->updatedField &&
            $sort !== $this->primaryKey &&
            $sort !== 'category'
        ) {
            $sort = 'property_' . $sort;
            $sort = in_array($sort, $this->allowedFields) || $sort === $this->primaryKey ? $sort : "";
        }

        if ($sort === "") {
            $sort = $this->primaryKey;
        }

        $this->orderBy($sort, strtolower($sortDir) === 'asc' ? 'ASC' : 'DESC');

        if ($sort !== 'property_priority') {
            $this->orderBy($this->table . '.property_priority', 'desc');
        }

        if ($sort !== 'property_tag') {
            $this->orderBy($this->table . '.property_tag', 'asc');
        }

        if ($sort !== 'property_value') {
            $this->orderBy($this->table . '.property_value', 'asc');
        }

        return $this;
    }

    protected function setupFilters(array $filters)
    {
        // can be used to filter by different field
        $identifier = $this->table . '.' . ($filters['id'] ?? 'property_tag');
        if (isset($filters['and']) && $filters['and'] !== []) {
            $this->whereIn($identifier, $filters['and']);
        }
        if (isset($filters['or']) && $filters['or'] !== []) {
            $this->orWhereIn($identifier, $filters['or']);
        }
        return $this;
    }

    protected function setupSearch(string $search)
    {
        if ($search === "") {
            return $this;
        }
        return $this->orLike("{$this->db->prefixTable($this->table)}.property_tag", $search, 'both', true, true)
                    ->orLike("{$this->db->prefixTable($this->table)}.property_value", $search, 'both', true, true);
    }

    protected function setupCategory(bool $category)
    {
        if ($category) {
            $this->join("{$this->table} AS c", "c.property_id = {$this->table}.property_tag", 'left')
                 ->select("{$this->table}.*, c.property_value AS category");
        }
        return $this;
    }

    /**
     * Override of the default 'delete' method.
     *
     * @param int $id     The id of the property to delete.
     * @param bool $purge If true, acts as a 'force' delete (ignores usage check).
     */
    public function delete($id = null, bool $purge = false)
    {
        $this->db->transStart();

        $item = $this->get((int) $id, ['usage' => !$purge]);

        if (!$item || (!$purge && $item->usage > 0)) {
            throw new ValidationException(
                "Cannot delete property: <strong>{$item->value}</strong>. " .
                "It is used by <strong>{$item->usage}</strong> materials."
            );
        }

        $item->children = $this->where('property_tag', $id)->findAll();
        if (!$purge && !empty($item->children)) {
            throw new ValidationException(
                "Cannot delete property: <strong>{$item->value}</strong>. " .
                "It contains nested tags."
            );
        }

        foreach ($item->children as $child) {
            $this->delete($child->id, $purge);
        }
        $result = parent::delete($id, $purge);

        $this->db->transComplete();

        return $result;
    }

    public function loadChildren(Property $property) : Property
    {
        return Cache::check(
            function () use ($property) {
                $children = [];
                foreach ($this->where('property_tag', $property->id)->getArray(['sort' => 'priority']) as $child) {
                    $children[] = $this->loadChildren($child);
                }
                $property->children = $children;
                return $property;
            },
            $property->id,
            "property",
        );
    }

    public function getTree() : Property
    {
        $root = new Property(['value' => 'root']);
        $root->children = Cache::check(
            function() {
                $categories = [];
                foreach ($this->where('property_tag', 0)->getArray(['sort' => 'priority']) as $category) {
                    $categories[] = $this->loadChildren($category);
                }
                return $categories;
            },
            "tree",
            "property",
        );
        return $root;
    }

    /** ----------------------------------------------------------------------
     *                              CALLBACKS
     *  ------------------------------------------------------------------- */

    protected function checkCache(array $data)
    {
        if (isset($data['id']) && $item = Cache::get($data['id'], 'property')) {
            $data['data']       = $item;
            $data['returnData'] = true;
        }
        return $data;
    }

    protected function saveCache(array $data)
    {
        if (!isset($data['data'])) {
            return $data;
        }
        if ($data['method'] !== 'findAll') {
            Cache::check(
                function () use ($data) {
                    return  $this->loadChildren($data['data']);
                },
                $data['data']->id,
                'property'
            );
        }
        return $data;
    }

    protected function revalidateCache(array $data)
    {
        if (!isset($data['id'])) {
            return;
        }
        foreach ($data['id'] as $id) {
            $item = Cache::get($id, 'property');
            if (!is_null($item)) {
                Cache::delete($id, 'property');
            }
            if (!is_null($item) && $item->tag === 0) {
                Cache::delete('tree', 'property');
            }
        }
    }

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
