<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Material;

class MaterialModel extends Model
{
    protected $table         = 'materials';
    protected $primaryKey    = 'material_id';
    protected $allowedFields = [
        'material_is_public',
        'material_title',
        'material_thumbnail',
        'material_type',
        'material_content',
        'material_views',
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $returnType = Material::class;

    public function findAll(int $limit = 0, int $offset = 0) : array
    {
        return $this->select('*')
                    ->where('material_is_public !=', false)
                    ->limit($limit, $offset * $limit)
                    ->get()
                    ->getCustomResultObject(Material::class);
    }

    public function findWithProperties(int $id) : Material|null
    {
        $material = $this->find($id);
        if (!$material || $material->material_is_public == false) {
            return null;
        }
        $material->properties = model(MaterialPropertyModel::class)->findProperties($id);
        return $material;
    }

    public function findFiltered(string $search, array $filters, int $limit, int $offset): array
    {
        $connector = model(MaterialPropertyModel::class);
        $filter = $connector->getCompiledFilter($filters);
        return $this->select("$this->table.*")
                    ->join("($filter) f", "$this->table.material_id = f.material_id")
                    ->like('material_title', $search, insensitiveSearch: true)
                    ->where('material_is_public !=', false)
                    ->limit($limit, $offset * $limit)
                    ->get()
                    ->getCustomResultObject(Material::class);
    }

    public function getUsedProperties() : array
    {
        $visibleIds = $this->select('material_id')
                           ->where('material_is_public !=', false)
                           ->get()
                           ->getResultArray();

        // unpack ids into array
        helper('array');
        $visibleIds = dot_array_search('*.material_id', $visibleIds);
        if (gettype($visibleIds) != 'array') $visibleIds = array($visibleIds);

        return ($visibleIds == [])
            ? $visibleIds
            : model(MaterialPropertyModel::class)->getUsedProperties($visibleIds);
    }
}
