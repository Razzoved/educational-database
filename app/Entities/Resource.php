<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Resource extends Entity
{
    protected $attributes = [
        'resource_id'    => null,
        'material_id'    => null,
        'resource_path'  => null,
        'resource_type'  => null,
        'created_at'     => null,
        'updated_at'     => null,
        'deleted_at'     => null,
    ];

    protected $datamap = [
        'id'       => 'resource_id',
        'parentId' => 'material_id',
        'path'     => 'resource_path',
        'type'     => 'resource_type',
    ];

    protected $casts = [
        'id'       => 'int',
        'parentId' => 'int',
        'path'     => 'string',
        'type'     => 'string',
    ];

    public function isLink() : bool
    {
        return $this->type == 'link';
    }

    public function getPath() : string
    {
        return ($this->isLink())
            ? $this->path
            : '/uploads/' . $this->parentId . '/' . $this->path . '.' . $this->type;
    }
}
