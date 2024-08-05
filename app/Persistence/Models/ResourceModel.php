<?php declare(strict_types=1);

namespace App\Persistence\Models;

use App\Persistence\Entities\Material;
use App\Persistence\Entities\Resource;
use CodeIgniter\Model;

class ResourceModel extends Model
{
    protected $table = 'resources';
    protected $primaryKey = Resource::ID;

    protected $allowedFields = [
        Resource::PARENT,
        Resource::PATH,
        Resource::TYPE,
    ];

    protected $useAutoIncrement = true;
    protected $useTimestamps = true;
    protected $useSoftDeletes = Resource::DELETED !== '';
    protected $createdField = Resource::CREATED;
    protected $updatedField = Resource::UPDATED;
    protected $deletedField = Resource::DELETED;
    protected $returnType = Resource::class;

    /*
     * --------------------------------------------------------------------
     *                           PUBLIC METHODS
     * --------------------------------------------------------------------
     */

    public function getResources(Material $material): array
    {
        return $this
            ->select('*')
            ->where(Resource::PARENT, $material->id)
            ->orderBy('type')
            ->orderBy('path')
            ->findAll();
    }

    public function getThumbnail(Material $material): array
    {
        return $this
            ->select('*')
            ->where(Resource::PARENT, $material->id)
            ->where('type', 'thumbnail')
            ->findAll();
    }
}
