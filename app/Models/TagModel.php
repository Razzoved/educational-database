<?php

namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $table      = 'tags';
    protected $primaryKey = 'tag_id';
    protected $useAutoIncrement = true;

    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = ['tag_id', 'tag_title'];

    // protected $useTimestamps = false;
    // protected $createdField  = 'post_created_at';
    // protected $updatedField  = 'post_updated_at';
    // protected $deletedField  = 'post_deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;
}