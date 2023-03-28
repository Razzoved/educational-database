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

    protected $returnType = Material::class;

    /**
     * Returns all materials WITHOUT any associated properties or resources.
     *
     * @param ?string $sor      key to sort by
     * @param ?string $sortDir  ASC or DESC
     *
     * @return MaterialModel    requires calling either 'paginate' or 'get'
     */
    public function getData(?string $sort = null, ?string $sortDir = null) : MaterialModel
    {
        $show = session('isLoggedIn') ? StatusCast::VALID_VALUES : array(StatusCast::PUBLIC);

        $sortDir = $sortDir && strtolower($sortDir) === 'desc' ? 'DESC' : 'ASC';
        $sort = $sort === $this->createdField || $sort === $this->updatedField
            ? $sort
            : (in_array('material_' . $sort, $this->allowedFields) || ('material_' . $sort === $this->primaryKey) ? ('material_' .  $sort) : null);

        $this->builder()
             ->whereIn('material_status', $show)
             ->orderBy($sort ?? $this->primaryKey, $sortDir);

        return $this;
    }

    /**
     * Returns all materials WITHOUT any associated properties or resources.
     *
     * @param ?string $sort     key to sort by
     * @param ?string $sortDir  ASC or DESC
     * @param string $search    title to search for
     * @param array $filters    filters to search with (has to match all)
     *
     * @return MaterialModel requires calling either 'paginate' or 'get'
     */
    public function getByFilters(?string $sort, ?string $sortDir, string $search, array $filters): MaterialModel
    {
        $builder = $this->getData($sort, $sortDir)->builder()
            ->select("$this->table.*")
            ->like('material_title', $search, 'both', true, true);

        if ($filters !== []) {
            $filter = model(MaterialPropertyModel::class)->getCompiledFilter($filters);
            $builder->join("($filter) f", "$this->table.material_id = f.material_id");
        }

        return $this;
    }

    /**
     * Returns a single material with all of its attributes loaded.
     *
     * @param int $id          id to search by
     * @return Material if found, else null
     */
    public function getById(int $id) : ?Material
    {
        $material = $this->find($id);
        return ($material) ? $this->verifyAndLoad($material) : null;
    }

    /**
     * Returns a single material with all of its attributes loaded.
     *
     * @param string $title    title to search by
     * @return Material if found, else null
     */
    public function getByTitle(string $title) : ?Material
    {
        $material = $this->builder()
                         ->where('material_title', $title)
                         ->get(1)
                         ->getCustomResultObject(Material::class);
        return ($material) ? $this->verifyAndLoad($material) : null;
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

    private function verifyAndLoad(Material $material) {
        if ($material->status != StatusCast::PUBLIC && !session('isLoggedIn')) {
            return null;
        }

        $material->properties = model(MaterialPropertyModel::class)->getByMaterial($material->id);
        $material->resources = model(ResourceModel::class)->getResources($material->id);
        $material->related = model(MaterialMaterialModel::class)->getRelated($material->id);

        return $material;
    }
}
