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

    public function getThumbnail(int $materialId) : array
    {
        return $this->builder()
                    ->where('material_id', $materialId)
                    ->where('resource_type', 'thumbnail')
                    ->get(1)
                    ->getCustomResultObject(Resource::class);
    }

    public function getByPath(int $materialId, int $path) : Resource
    {
        return $this->builder()
                    ->where('material_id', $materialId)
                    ->where('resource_path', $path)
                    ->get()
                    ->getCustomRowObject(1, Resource::class);
    }

    public function handleUpdate(Material $material, $db = null) : void
    {
        if (!isset($db)) $db = $this->db;

        $oldResources = $this->getResources($material->id);

        $toDelete = array_filter($oldResources, function($r) use ($material) {
            return !\App\Libraries\Arrays::valueExists($r, $material->resources, function($a, $b) {
                return $a->path === $b->name && $a->type === $b->type;
            });
        });

        $toCreate = array_filter($material->resources, function($r) use ($oldResources) {
            return !\App\Libraries\Arrays::valueExists($r, $oldResources, function($a, $b) {
                return $a->name === $b->path && $a->type === $b->type;
            });
        });

        foreach ($toDelete as $r) {
            $db->table($this->table)
               ->where('resource_id', $r->id)
               ->where('material_id', $material->id)
               ->delete();
        }

        foreach ($toCreate as $r) {
            $db->table($this->table)->insert([
                'material_id'   => $material->id,
                'resource_path' => $r->name,
                'resource_type' => $r->type,
            ]);
        }
    }
}
