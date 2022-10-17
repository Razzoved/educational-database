<?php

namespace App\Models;

use CodeIgniter\Model;

class PostTagModel extends Model
{
    protected $table = 'post_tags';
    //protected $primaryKey = '';
    //protected $useAutoIncrement = true;

    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = ['post_id', 'tag_id', 'tag_order'];

    // protected $useTimestamps = false;
    // protected $createdField  = 'post_created_at';
    // protected $updatedField  = 'post_updated_at';
    // protected $deletedField  = 'post_deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;
}