<?php declare(strict_types = 1);

namespace App\Models\Tables;

use CodeIgniter\Model;

class PropertyModel extends Model
{
    protected $table      = 'properties';
    protected $primaryKey = 'property_id';
    protected $useAutoIncrement = true;

    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = ['property_type', 'property_value'];

    protected $useTimestamps = true;
    protected $createdField  = 'post_created_at';
    protected $updatedField  = 'post_updated_at';
    protected $deletedField  = 'post_deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;
}