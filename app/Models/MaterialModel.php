<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\Cast\StatusCast;
use App\Entities\Material;
use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table         = 'materials';
    protected $primaryKey    = 'material_id';
    protected $allowedFields = [
        'material_status',
        'material_title',
        'material_author',
        'material_blame',
        'material_content',
        'material_views',
        'material_rating',
        'material_rating_count'
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'title'   => 'required|string',
        'author'  => 'required|string',
        'status'  => 'required|validStatus',
        'content' => 'string',
    ];
    protected $validationMessages = [
        'title'  => [
            'required' => 'Title must be present.',
            'string'   => 'Title must be a valid string.'
        ],
        'author'  => [
            'required' => 'Author must be present.',
            'string'   => 'Author must be a valid string.'
        ],
        'status' => [
            'required'    => 'Status must be present.',
            'validStatus' => 'Invalid status.'
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

    public function get(int $id, array $data = []) : ?Material
    {
        return $this->setupQuery($data)->find($id);
    }

    public function getArray(array $data = []) : array
    {
        return $this->setupQuery($data)->findAll();
    }

    public function getPage(int $page = 1, array $data = [], int $perPage = 20) : array
    {
        return $this->setupQuery($data)->paginate($perPage, 'default', null, $page);
    }

    /** ----------------------------------------------------------------------
     *                        UNIFIED QUERY SETUP
     *  ------------------------------------------------------------------- */

    protected function setupQuery(array $data = []) : MaterialModel
    {
        return $this
            ->setupSort($data['sort'] ?? "", $data['sortDir'] ?? "")
            ->setupFilters($data['filters'] ?? [])
            ->setupSearch($data['search'] ?? "")
            ->setupShow(session()->has('isLoggedIn') && session('isLoggedIn') === true)
            ->allowCallbacks(!isset($data['callbacks']) || $data['callbacks'] === true);

        return $this;
    }

    protected function setupSort(string $sort, string $sortDir)
    {
        if (
            $sort !== $this->createdField &&
            $sort !== $this->updatedField &&
            $sort !== $this->primaryKey
        ) {
            $sort = 'material_' . $sort;
            $sort = in_array($sort, $this->allowedFields) || $sort === $this->primaryKey ? $sort : "";
        }

        if ($sort === "") {
            $sort = $this->primaryKey;
        }

        return $this->orderBy(
            $sort,
            strtolower($sortDir) === 'desc' ? 'DESC' : 'ASC'
        );
    }

    protected function setupFilters(array $filters)
    {
        if ($filters !== []) {
            $filter = model(MaterialPropertyModel::class)->getCompiledFilter($filters);
            $this->join("($filter) f", "$this->table.material_id = f.material_id");
        }
        return $this;
    }

    protected function setupSearch(string $search)
    {
        if ($search === "") {
            return $this;
        }
        return $this->like('material_title', $search, 'both', true, true);
    }

    protected function setupShow(bool $admin)
    {
        if ($admin) {
            $this->whereIn('material_status', StatusCast::VALID_VALUES);
        } else {
            $this->where('material_status', StatusCast::PUBLIC);
        }
        return $this;
    }

    /**
     * Returns all 'authors', along with the total of posts under their name.
     */
    public function getContributors() : array
    {
        return $this->builder()
                    ->select('material_author as contributor')
                    ->selectCount('*', 'total_posts')
                    ->groupBy('material_author')
                    ->orderBy('total_posts', 'desc')
                    ->get()
                    ->getResultArray();
    }

    public function handleUpdate(Material $material, array $relatedMaterials = []) : int
    {
        $material->blame = session('user')->id;
        $m = $this->find($material->id);

        $this->db->transStart();

        if ($m !== null) {
            $material->views = $m->views;
            $material->rating = $m->rating;
            $material->rating_count = $m->rating_count;
            $this->update($material->id, $material);
        } else {
            $material->views = 0;
            $material->rating = 0;
            $material->rating_count = 0;
            $material->id = $this->insert($material, true);
        }

        model(MaterialMaterialModel::class)->handleUpdate($material, $relatedMaterials, $this->db);
        model(MaterialPropertyModel::class)->handleUpdate($material, $this->db);

        $this->db->transComplete();

        return $material->id;
    }

    /** ----------------------------------------------------------------------
     *                              CALLBACKS
     *  ------------------------------------------------------------------- */

    protected function loadResources(array $data)
    {
        if (!isset($data['data'])) {
            return $data;
        }

        // single material
        $material = $data['data'];
        if (is_a($material, Material::class)) {
            $material->resources = model(ResourceModel::class)->getResources($material->id);
            return $data;
        }

        // multiple materials, limit only to thumbnail
        $materials = $data['data'];
        foreach ($materials as $material) {
            $material->resources = model(ResourceModel::class)->getThumbnail($material->id);
        }
        return $data;
    }

    protected function loadRelations(array $data)
    {
        if (!isset($data['data'])) {
            return $data;
        }

        // single material
        $material = $data['data'];
        if (is_a($material, Material::class)) {
            $material->related = model(MaterialMaterialModel::class)->getRelated($material->id);
        }

        return $data;
    }

    protected function loadProperties(array $data)
    {
        if (!isset($data['data'])) {
            return $data;
        }

        // single material
        $material = $data['data'];
        if (is_a($material, Material::class)) {
            $material->properties = model(MaterialPropertyModel::class)->getByMaterial($material->id);
        }

        return $data;
    }
}
