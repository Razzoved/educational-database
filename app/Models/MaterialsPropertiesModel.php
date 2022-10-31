<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;

class MaterialsProperties extends Model
{
    protected $table = 'materials_properties';
    protected $allowedFields = ['material_id', 'property_id'];
}
