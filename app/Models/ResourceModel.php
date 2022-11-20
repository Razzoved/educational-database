<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Resource;

class ResourceModel extends Model
{
    protected $table         = 'resources';
    protected $primaryKey    = 'resource_id';
    protected $allowedFields = [
        'material_id',
        'resource_path',
        'resource_type',
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $returnType = Resource::class;

    public function getResources(int $postId) : array
    {
        return $this->select("*")
                    ->where('material_id', $postId)
                    ->orderBy('resource_type')
                    ->orderBy('resource_path')
                    ->get()
                    ->getCustomResultObject(Resource::class);
    }
}
