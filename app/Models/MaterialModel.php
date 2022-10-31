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
        'material_title',
        'material_path'
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    protected $createdField  = 'material_created_at';
    protected $updatedField  = 'material_updated_at';

    protected $returnType = Material::class;
}
