<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\Property;

class PropertyModel extends AbstractModel
{
    protected $table         = parent::PREFIX . 'properties';
    protected $primaryKey    = 'property_id';
    protected $allowedFields = [
        'property_tag',
        'property_value'
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    protected $returnType = Property::class;

    public function getData(?string $sort = null, ?string $sortDir = null) : PropertyModel
    {
        $sortDir = $sortDir === 'DESC' ? $sortDir : 'ASC';
        $sort = in_array('property_' . $sort, $this->allowedFields) || ('property_' . $sort === $this->primaryKey) ? ('property_' . $sort) : null;

        $builder = $this->builder()->distinct();

        if (!is_null($sort)) {
            $builder->orderBy($sort, $sortDir);
        }

        if ($sort !== 'property_tag') {
            $builder->orderBy('property_tag');
        }

        if ($sort !== 'property_value') {
            $builder->orderBy('property_value');
        }

        return $this;
    }

    public function getByFilters(?string $sort, ?string $sortDir, string $search, array $filters) : PropertyModel
    {
        $builder = $this->getData($sort, $sortDir);

        if ($search !== "") {
            $builder->orLike('property_tag', $search)
                    ->orLike('property_value', $search);
        }

        if (isset($filters['Tags'])) {
            $builder->whereIn('property_tag', array_keys($filters['Tags']));
        }

        if (isset($filters['Values'])) {
            $builder->groupStart();
            foreach ($filters['Values'] as $value => $state) {
                $builder->orLike('property_value', $value, 'after');
            }
            $builder->groupEnd();
        }

        return $this;
    }

    public function getByType(string $type) : PropertyModel
    {
        $this->builder()
             ->where('property_tag', $type)
             ->orderBy('property_tag')
             ->orderBy('property_value')
             ->distinct();
        return $this;
    }

    public function getByBoth(string $tag, string $value) : ?Property
    {
        return $this->builder()
             ->where('property_tag', $tag)
             ->where('property_value', $value)
             ->get(1)
             ->getCustomRowObject(1, Property::class);
    }

    /**
     * Returns all tags (non-duplicate).
     *
     * @return array all tag names
     */
    public function getTags() : array
    {
        return array_column($this->builder()
                                 ->select('property_tag')
                                 ->distinct()
                                 ->orderBy('property_tag')
                                 ->get()
                                 ->getResultArray(), 'property_tag');
    }

    /**
     * Returns all property values that are used more than once.
     *
     * @return array all values used more than once
     */
    public function getDuplicateValues() : array
    {
        return array_column($this->builder()
                                 ->select("property_value", false)
                                 ->orderBy('property_value')
                                 ->groupBy('property_value')
                                 ->having('COUNT(*) >', 1)
                                 ->get()
                                 ->getResultArray(), 'property_value');
    }
}
