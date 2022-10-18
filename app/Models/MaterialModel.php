<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table      = 'materials';
    protected $primaryKey = 'material_id';
    protected $useAutoIncrement = true;

    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = ['post_id', 'material_title', 'material_path'];

    protected $useTimestamps = true;
    protected $createdField  = 'material_created_at';
    protected $updatedField  = 'material_updated_at';
    protected $deletedField  = 'material_deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;
}