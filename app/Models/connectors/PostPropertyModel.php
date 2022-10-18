<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;

class PostPropertyModel extends Model
{
    protected $table = 'post_property';
    //protected $primaryKey = '';
    //protected $useAutoIncrement = true;

    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = ['post_id', 'property_id'];

    // protected $useTimestamps = false;
    // protected $createdField  = 'post_created_at';
    // protected $updatedField  = 'post_updated_at';
    // protected $deletedField  = 'post_deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;
}