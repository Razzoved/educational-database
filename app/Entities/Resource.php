<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Resource extends Entity
{
    public const NO_THUMBNAIL_PATH = 'assets/no_thumbnail.png';
    public const NO_THUMBNAIL = [
        'resource_path' => Resource::NO_THUMBNAIL_PATH,
        'resource_type' => 'thumbnail',
    ];

    protected $attributes = [
        'resource_id'    => null,
        'material_id'    => null,
        'resource_path'  => null,
        'resource_type'  => null,
        'created_at'     => null,
        'updated_at'     => null,
        'deleted_at'     => null,
        'tmp_path'       => null, // not a part of db
    ];

    protected $casts = [
        'resource_id'   => 'int',
        'material_id'   => 'int',
        'resource_path' => 'string',
        'resource_type' => 'string',
    ];

    protected $datamap = [
        'id'       => 'resource_id',
        'parentId' => 'material_id',
        'path'     => 'resource_path',
        'type'     => 'resource_type',
    ];

    public function isLink() : bool
    {
        return $this->type == 'link';
    }

    public function isThumbnail() : bool
    {
        return $this->type == 'thumbnail';
    }

    public function getName() : string
    {
        return $this->path;
    }

    public function getPath(bool $src = true) : string
    {
        $path = $this->path;

        if (!$this->isLink()) {
            $path = $src ? (base_url() . '/') : '';
            $path .= isset($this->parentId) ? ('uploads/' . $this->parentId . '/') : '';
            $path .= $this->getName();
        }

        return $path;
    }
}
