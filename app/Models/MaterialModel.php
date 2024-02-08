<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\Cast\StatusCast;
use App\Entities\Material;

/**
 * This model encompases most operations on materials over the database.
 * Handles all loading operations by default (can be disabled by disabling
 * callbacks on getters) - this means loading of resources, relations, and
 * other data.
 *
 * @author Jan Martinek
 */
class MaterialModel extends QueryModel
{
    protected $table         = 'materials';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'status',
        'title',
        'content',
        'views',
        'rating',
        'rating_count',
        'published_at',
        'updated_at',
        'user_id',
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;

    protected $validationRules = [
        'title'   => 'required|string',
        'status'  => 'required|valid_status',
        'content' => 'string',
        'user_id' => 'required',
    ];
    protected $validationMessages = [
        'title'  => [
            'required' => 'Title must be present.',
            'string'   => 'Title must be a valid string.'
        ],
        'status' => [
            'required'     => 'Status must be present.',
            'valid_status' => 'Invalid status.'
        ],
        'content' => [
            'string' => 'Content must be a valid string.'
        ]
    ];

    protected $afterFind = [
        'loadResources',
        'loadRelations',
        'loadProperties'
    ];

    protected $returnType = Material::class;

    /** ----------------------------------------------------------------------
     *                           PUBLIC METHODS
     *  ------------------------------------------------------------------- */

    public function getBlame(): array
    {
        $userModel = model(UserModel::class);
        return $userModel
            ->select('name')
            ->selectCount('*', 'total_posts')
            ->join("{$this->table} m", "{$userModel->table}.id=m.user_id")
            ->groupBy('m.user_id')
            ->orderBy('total_posts', 'desc')
            ->findAll();
    }

    public function saveMaterial(Material $material): int
    {
        if (!$material) {
            throw new \InvalidArgumentException('Cannot save "null" material');
        }

        $m = $this->find($material->id);

        $material->blame = session('user')->id;
        $material->views = $m->views ?? 0;
        $material->rating = $m->rating ?? 0;
        $material->rating_count = $m->rating_count ?? 0;
        $material->updated_at = $this->setDate();

        if ($material->status !== StatusCast::PUBLIC) {
            $material->published_at = null;
        } else if (!$m || $m->status !== StatusCast::PUBLIC) {
            $material->published_at = $material->updated_at;
        }

        $this->db->transException(true);
        $this->db->transStart();

        if ($m) {
            $this->update($material->id, $material->toRawArray());
        } else {
            $material->id = $this->insert($material, true);
        }

        model(MaterialMaterialModel::class)->saveMaterial($material);
        model(MaterialPropertyModel::class)->saveMaterial($material);

        $this->db->transComplete();

        return $material->id;
    }

    /** ----------------------------------------------------------------------
     *                        UNIFIED QUERY SETUP
     *  ------------------------------------------------------------------- */

    protected function beforeQuery(array $data = []): MaterialModel
    {
        return $this
            ->setupSort($data)
            ->setupFilters($data['filters'] ?? [])
            ->setupSearch($data['search'] ?? "")
            ->setupShow(session()->has('isLoggedIn') && session('isLoggedIn') === true);
    }

    protected function setupSort(array $data)
    {
        $this->sortBy($data, 'published_at');
        $this->sortDir($data);

        $this->orderBy($data['sortBy'], $data['sortDir']);

        if ($data['sortBy'] !== 'published_at') {
            $this->orderBy('published_at', 'desc');
        }
        if ($data['sortBy'] !== $this->updatedField) {
            $this->orderBy($this->updatedField, 'desc');
        }
        if ($data['sortBy'] !== 'rating') {
            $this->orderBy('rating', 'desc');
        }
        if ($data['sortBy'] !== 'rating_count') {
            $this->orderBy('rating_count', 'desc');
        }
        if ($data['sortBy'] !== 'views') {
            $this->orderBy('views', 'desc');
        }

        return $this;
    }

    protected function setupFilters(array $filters)
    {
        if ($filters !== []) {
            $filter = model(MaterialPropertyModel::class)->getCompiledFilter($filters);
            $this->whereIn('id', $filter);
        }
        return $this;
    }

    protected function setupSearch(string $search)
    {
        return $search === "" ? $this : $this->like('title', $search, 'both', true, true);
    }

    protected function setupShow(bool $admin)
    {
        if ($admin) {
            $this->whereIn('status', StatusCast::VALID_VALUES);
        } else {
            $this->where('status', StatusCast::PUBLIC);
        }
        return $this;
    }

    /** ----------------------------------------------------------------------
     *                              CALLBACKS
     *  ------------------------------------------------------------------- */

    protected function loadResources(array $data)
    {
        if (!isset($data['data'])) {
            return $data;
        }
        if ($data['method'] === 'find') {
            $data['data']->resources = model(ResourceModel::class)->getResources($data['data']->id);
        } else foreach ($data['data'] as $material) {
            if ($material) {
                $material->resources = model(ResourceModel::class)->getThumbnail($material->id);
            }
        }
        return $data;
    }

    protected function loadRelations(array $data)
    {
        if (!isset($data['data'])) {
            return $data;
        }
        if ($data['method'] === 'find') {
            $data['data']->related = model(MaterialMaterialModel::class)->getRelated($data['data']->id);
        }
        return $data;
    }

    protected function loadProperties(array $data)
    {
        if (!isset($data['data'])) {
            return $data;
        }
        if ($data['method'] === 'find') {
            $data['data']->properties = model(MaterialPropertyModel::class)->find($data['data']->id);
        }
        return $data;
    }
}
