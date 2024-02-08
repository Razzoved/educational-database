<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\Material;
use App\Entities\Resource;
use CodeIgniter\Model;

class ResourceModel extends Model
{
    protected $table         = 'resources';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'material_id',
        'path',
        'type',
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $returnType = Resource::class;

    public function getResources(int $materialId): array
    {
        return $this->where('material_id', $materialId)
            ->orderBy('type')
            ->orderBy('path')
            ->findAll();
    }

    public function getThumbnail(int $materialId): array
    {
        return $this->where('material_id', $materialId)
            ->where('type', 'thumbnail')
            ->findAll();
    }

    public function getByPath(int $materialId, string $path): ?Resource
    {
        return $this->where('material_id', $materialId)
            ->where('path', $path)
            ->first();
    }
}
