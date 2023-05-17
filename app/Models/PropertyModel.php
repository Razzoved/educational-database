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
    private const CAT_ALIAS = 'categories';
    private const USG_ALIAS = 'usages';

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
    protected $afterInsert = [
        'revalidateCache',
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

    public function find($id = null) : ?Property
    {
        // reduces the number of callback calls
        return $id == 0
            ? null
            : parent::find($id);
    }

    public function get(int $id, array $data = []) : ?Property
    {
        return $this->setupQuery($data, $id)->find($id);
    }

    public function getArray(array $data = [], int $limit = 0) : array
    {
        return $this->setupQuery($data)->findAll($limit);
    }

    public function getPage(int $page = 1, array $data = [], int $perPage = 20) : array
    {
        return $this->setupQuery($data)->paginate($perPage, 'default', $page);
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

    public function getTree() : Property
    {
        $root = new Property(['value' => 'Categories']);
        $root->children = Cache::check(
            function() {
                $categories = [];
                foreach ($this->where('property_tag', 0)->getArray() as $category) {
                    $categories[] = $this->getTreeRecursive($category);
                }
                return $categories;
            },
            "tree",
            "property",
        );
        return $root;
    }

    /**
     * Override of the default 'delete' method.
     *
     * @param int $id     The id of the property to delete.
     * @param bool $purge If true, acts as a 'force' delete (ignores usage check).
     */
    public function delete($id = null, bool $purge = false)
    {
        $this->db->transException(true);
        $this->db->transStart();

        $item = $this->get((int) $id);

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

    /** ----------------------------------------------------------------------
     *                        UNIFIED QUERY SETUP
     *  ------------------------------------------------------------------- */

    protected function setupQuery(array $data = [], ?int $id = null) : PropertyModel
    {
        if (isset($data['callbacks'])) {
            $this->allowCallbacks($data['callbacks'] === true);
        }
        return $this
            ->setupSelect($id, $data)
            ->setupSearch($data['search'] ?? "")
            ->setupFilters($data['filters'] ?? [])
            ->setupSort($data['sort'] ?? "", $data['sortDir'] ?? "");
    }

    protected function setupSelect(?int $id, array $data)
    {
        $category = self::CAT_ALIAS;
        $usage = self::USG_ALIAS;

        $usageQuery = model(MaterialPropertyModel::class)->builder()
            ->select('property_id')
            ->selectCount('property_id', 'usage')
            ->groupBy('property_id');
        if (!is_null($id)) {
            $usageQuery = $usageQuery->where('property_id', $id);
        }
        $usageQuery = $usageQuery->getCompiledSelect();

        $this->select("{$this->table}.*, {$usage}.usage as usage, {$category}.property_value as category")
             ->join("{$this->table} as {$category}", "{$this->table}.property_tag = {$category}.property_id", 'left')
             ->join("({$usageQuery}) as {$usage}", "{$this->table}.property_id = {$usage}.property_id", 'left');

        $sort = $data['sort'] ?? false;
        $sortDir = isset($data['sortDir']) && strtoupper($data['sortDir']) === 'ASC' ? 'ASC' : 'DESC';

        if ($sort === 'category') {
            $this->orderBy('property_value', $sortDir, null, $category);
        }
        if ($sort === 'usage') {
            $this->orderBy('usage', $sortDir, null, $usage);
        }

        return $this;
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

        if ($sort === "") {
            $sort = 'property_priority';
        }

        $this->orderBy($sort, strtolower($sortDir) === 'asc' ? 'ASC' : 'DESC');

        if ($sort !== 'property_priority') {
            $this->orderBy('property_priority', 'desc');
        }
        if ($sort !== 'property_tag') {
            $this->orderBy('property_tag');
        }
        if ($sort !== 'property_value') {
            $this->orderBy('property_value');
        }
        if ($sort !== $this->primaryKey) {
            $this->orderBy($this->primaryKey);
        }

        return $this;
    }

    /**
     * Does OR on all filters, aimed at admin table for tags.
     */
    protected function setupFilters(array $filters)
    {
        foreach ($filters['or'] ?? [] as $ids) {
            if (is_array($ids)) {
                $this->orGroupStart();
                $this->orWhereIn("{$this->table}.property_id", $ids);
                $this->orWhereIn("{$this->table}.property_tag", $ids);
                $this->groupEnd();
            }
        }
        if (isset($filters['and']) && is_array($filters['and'])) {
            $this->orGroupStart();
            $this->orWhereIn("{$this->table}.property_id", $filters['and']);
            $this->orWhereIn("{$this->table}.property_tag", $filters['and']);
            $this->groupEnd();
        }
        return $this;
    }

    protected function setupSearch(string $search)
    {
        if ($search === "") {
            return $this;
        }
        return $this->orLike("property_value", $search, 'both', true, true, self::CAT_ALIAS)
                    ->orLike("property_value", $search, 'both', true, true);
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
            $data['data'] = Cache::check(
                function () use ($data) {
                    return  $this->getTreeRecursive($data['data']);
                },
                $data['data']->id,
                'property',
                3600 * 12,
            );
        }
        return $data;
    }

    public function revalidateCache(array $data)
    {
        if (!isset($data['id'])) {
            return;
        }

        $ids = is_array($data['id'])
            ? $data['id']
            : array($data['id']);

        foreach ($ids as $id) {
            $item = Cache::get($id, 'property');
            $this->_revalidateCache($item);
            unset($id);
        }

        if (isset($data['data']['property_tag'])) {
            $item = Cache::get($data['data']['property_tag'], 'property');
            $this->_revalidateCache($item);
        }

        return $data;
    }

    protected function _revalidateCache(?Property $item)
    {
        while (!is_null($item)) {
            Cache::delete($item->id, 'property');
            $item = Cache::get($item->tag, 'property');
        }
        Cache::delete('tree', 'property');
    }

    /** ----------------------------------------------------------------------
     *                              HELPERS
     *  ------------------------------------------------------------------- */

    public function getTreeRecursive(Property $property) : Property
    {
        return Cache::check(
            function () use ($property) {
                $children = [];
                foreach ($this->where('property_tag', $property->id)->getArray() as $child) {
                    $children[] = $this->getTreeRecursive($child);
                }
                $property->children = $children;
                return $property;
            },
            $property->id,
            "property",
        );
    }

    public function where(string $field, $value = null, $escape = null, $prefix = null) : PropertyModel
    {
        $prefix = $prefix ?? $this->table;
        if ($prefix !== '') {
            $prefix .= '.';
        }
        return parent::where($prefix . $field, $value, $escape);
    }

    public function orLike(string $field, string $match = '', string $side = 'both', $escape = null, $insensitiveSearch = false, $prefix = null) : PropertyModel
    {
        $prefix = $prefix ?? "{$this->db->prefixTable($this->table)}";
        if ($prefix !== '') {
            $prefix .= '.';
        }
        return parent::orLike($prefix . $field, $match, $side, $escape, $insensitiveSearch);
    }

    public function orderBy(string $field, string $direction = '', $escape = null, $prefix = null) : PropertyModel
    {
        $prefix = $prefix ?? $this->table;
        if ($prefix !== '') {
            $prefix .= '.';
        }
        return parent::orderBy($prefix . $field, $direction, $escape);
    }
}
