<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Material;

class MaterialModel extends Model
{
    protected $table         = 'materials';
    protected $primaryKey    = 'material_id';
    protected $allowedFields = [
        'post_id',
        'material_path',
        'material_type',
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $returnType = Material::class;

    public function findMaterials(int $postId) : array
    {
        return $this->select("*")
                    ->where('post_id', $postId)
                    ->orderBy('material_type')
                    ->orderBy('material_path')
                    ->get()
                    ->getCustomResultObject(Material::class);
    }
}
