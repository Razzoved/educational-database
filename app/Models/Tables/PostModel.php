<?php declare(strict_types = 1);

namespace App\Models\Tables;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table      = 'posts';
    protected $primaryKey = 'post_id';
    protected $useAutoIncrement = true;

    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = ['post_title', 'post_type', 'post_content'];

    protected $useTimestamps = true;
    protected $createdField  = 'post_created_at';
    protected $updatedField  = 'post_updated_at';
    protected $deletedField  = 'post_deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;
}