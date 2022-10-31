<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;

class MaterialProperty extends Model
{
    protected $table = 'material_property';
    protected $allowedFields = ['material_id', 'property_id'];
}
