<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;

class PostPropertyModel extends Model
{
    protected $table = 'post_property';
    protected $allowedFields = ['post_id', 'property_id'];
}
