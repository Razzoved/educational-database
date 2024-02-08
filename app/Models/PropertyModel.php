<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\Property;
use App\Libraries\Cache;
use CodeIgniter\Validation\Exceptions\ValidationException;

/**
 * Model that handles all operations on properties.
 * Those operations include connections to materials.
 *
 * @author Jan Martinek
 */
class PropertyModel extends QueryModel
{
    private const CAT_ALIAS = 'categories';
    private const USG_ALIAS = 'usages';

    protected $table         = 'properties';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'parent',
        'value',
        'priority',
        'description',
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

    public function find($id = null, array $data = []): ?Property
    {
        // reduces the number of callback calls
        return $id == 0
            ? null
            : parent::find($id, $data);
    }

    public function asTree(): Property
    {
        return $this->asTreeFrom(new Property([
            'id' => 0,
            'value' => 'Categories'
        ]));
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

        $item = $this->find((int) $id);

        if (!$item || (!$purge && $item->usage > 0)) {
            throw new ValidationException(
                "Cannot delete property: <strong>{$item->value}</strong>. " .
                    "It is used by <strong>{$item->usage}</strong> materials."
            );
        }

        $item->children = $this->where('parent', $id)->findAll();
        if (!$purge && !empty($item->children)) {
            throw new ValidationException(
                "Cannot delete property: <strong>{$item->value}</strong>. " .
                    "It contains nested parents."
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

    protected function beforeQuery(array $data = []): PropertyModel
    {
        return $this
            ->setupSelect($data['id'] ?? null, $data)
            ->setupSearch($data['search'] ?? "")
            ->setupFilters($data['filters'] ?? [])
            ->setupSort($data);
    }

    protected function setupSelect(?int $id, array $data)
    {
        $category = self::CAT_ALIAS;
        $usage = self::USG_ALIAS;

        $usageQuery = model(MaterialPropertyModel::class)->builder()
            ->select('id')
            ->selectCount('id', 'usage')
            ->groupBy('id');
        if (!is_null($id)) {
            $usageQuery = $usageQuery->where('id', $id);
        }
        $usageQuery = $usageQuery->getCompiledSelect();

        $this->select("{$this->table}.*, {$usage}.usage as usage, {$category}.value as category")
            ->join("{$this->table} as {$category}", "{$this->table}.parent = {$category}.id", 'left')
            ->join("({$usageQuery}) as {$usage}", "{$this->table}.id = {$usage}.id", 'left');

        $sort = $data['sortBy'] ?? false;
        $sortDir = isset($data['sortDir']) && strtoupper($data['sortDir']) === 'ASC' ? 'ASC' : 'DESC';

        if ($sort === 'category') {
            $this->orderBy('value', $sortDir, null, $category);
        }
        if ($sort === 'usage') {
            $this->orderBy('usage', $sortDir, null, $usage);
        }

        return $this;
    }

    protected function setupSort(array $data)
    {
        $this->sortBy($data, 'priority');
        $this->sortDir($data);

        $this->orderBy($data['sortBy'], $data['sortDir']);

        if ($data['sortBy'] !== 'priority') {
            $this->orderBy('priority', 'desc');
        }
        if ($data['sortBy'] !== 'parent') {
            $this->orderBy('parent');
        }
        if ($data['sortBy'] !== 'value') {
            $this->orderBy('value');
        }
        if ($data['sortBy'] !== $this->primaryKey) {
            $this->orderBy($this->primaryKey);
        }

        return $this;
    }

    /**
     * Does OR on all filters, aimed at admin table for parents.
     */
    protected function setupFilters(array $filters)
    {
        foreach ($filters['or'] ?? [] as $ids) {
            if (is_array($ids)) {
                $this->orGroupStart();
                $this->orWhereIn("{$this->table}.id", $ids);
                $this->orWhereIn("{$this->table}.parent", $ids);
                $this->groupEnd();
            }
        }
        if (isset($filters['and']) && is_array($filters['and'])) {
            $this->orGroupStart();
            $this->orWhereIn("{$this->table}.id", $filters['and']);
            $this->orWhereIn("{$this->table}.parent", $filters['and']);
            $this->groupEnd();
        }
        return $this;
    }

    protected function setupSearch(string $search)
    {
        if ($search === "") {
            return $this;
        }
        return $this->orLike("value", $search, 'both', true, true, self::CAT_ALIAS)
            ->orLike("value", $search, 'both', true, true);
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
                    return  $this->asTreeFrom($data['data']);
                },
                $data['data']->id,
                'property',
                115200, // 3600 * 32
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

        if (isset($data['data']['parent'])) {
            $item = Cache::get($data['data']['parent'], 'property');
            $this->_revalidateCache($item);
        }

        return $data;
    }

    protected function _revalidateCache(?Property $item)
    {
        while (!is_null($item)) {
            Cache::delete($item->id, 'property');
            $item = Cache::get($item->parent, 'property');
        }
        Cache::delete('tree', 'property');
    }

    /** ----------------------------------------------------------------------
     *                              HELPERS
     *  ------------------------------------------------------------------- */

    public function asTreeFrom(Property $property): Property
    {
        return Cache::check(
            function () use ($property) {
                $children = [];
                foreach ($this->where('parent', $property->id)->findAll() as $child) {
                    $children[] = $this->asTreeFrom($child);
                }
                $property->children = $children;
                return $property;
            },
            $property->id,
            "property",
        );
    }

    public function where(string $field, $value = null, $escape = null, $prefix = null): PropertyModel
    {
        $prefix = $prefix ?? $this->table;
        if ($prefix !== '') {
            $prefix .= '.';
        }
        return parent::where($prefix . $field, $value, $escape);
    }

    public function orLike(string $field, string $match = '', string $side = 'both', $escape = null, $insensitiveSearch = false, $prefix = null): PropertyModel
    {
        $prefix = $prefix ?? "{$this->db->prefixTable($this->table)}";
        if ($prefix !== '') {
            $prefix .= '.';
        }
        return parent::orLike($prefix . $field, $match, $side, $escape, $insensitiveSearch);
    }

    public function orderBy(string $field, string $direction = '', $escape = null, $prefix = null): PropertyModel
    {
        $prefix = $prefix ?? $this->table;
        if ($prefix !== '') {
            $prefix .= '.';
        }
        return parent::orderBy($prefix . $field, $direction, $escape);
    }
}
