<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Property;

class PropertyModel extends Model
{
    protected $table         = 'properties';
    protected $primaryKey    = 'property_id';
    protected $allowedFields = [
        'property_tag',
        'property_value'
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    protected $createdField  = 'post_created_at';
    protected $updatedField  = 'post_updated_at';

    protected $returnType = Property::class;
}
