<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\Material;
use App\Entities\Resource;
use CodeIgniter\Model;

class ResourceModel extends Model
{
    protected $table         = 'resources';
    protected $primaryKey    = 'resource_id';
    protected $allowedFields = [
        'material_id',
        'resource_id',
        'resource_path',
        'resource_type',
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $returnType = Resource::class;

    public function getResources(int $materialId) : array
    {
        return $this->builder()
                    ->where('material_id', $materialId)
                    ->orderBy('resource_type')
                    ->orderBy('resource_path')
                    ->get()
                    ->getCustomResultObject(Resource::class);
    }

    public function getThumbnail(int $materialId) : ?Resource
    {
        return $this->builder()
                    ->where('material_id', $materialId)
                    ->where('resource_type', 'thumbnail')
                    ->get()
                    ->getCustomRowObject(0, Resource::class);
    }

    public function getByPath(int $materialId, string $path) : ?Resource
    {
        return $this->builder()
                    ->where('material_id', $materialId)
                    ->where('resource_path', $path)
                    ->get()
                    ->getCustomRowObject(0, Resource::class);
    }
}
