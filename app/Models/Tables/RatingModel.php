<?php declare(strict_types = 1);

namespace App\Models\Tables;

use CodeIgniter\Model;

class RatingModel extends Model
{
    protected $table = 'ratings';
    //protected $primaryKey = '';
    //protected $useAutoIncrement = true;

    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = ['client_token', 'post_id', 'client_rating'];

    // protected $useTimestamps = false;
    // protected $createdField  = 'post_created_at';
    // protected $updatedField  = 'post_updated_at';
    // protected $deletedField  = 'post_deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;
}