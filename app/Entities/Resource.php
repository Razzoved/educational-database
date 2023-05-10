<?php declare(strict_types = 1);

namespace App\Entities;

use App\Entities\Cast\PathCast;
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
        'tmp_path'       => null, // not a part of db
    ];

    protected $casts = [
        'resource_id'   => 'int',
        'material_id'   => 'int',
        'resource_path' => 'path',
        'resource_type' => 'path',
    ];

    protected $datamap = [
        'id'       => 'resource_id',
        'parentId' => 'material_id',
        'path'     => 'resource_path',
        'type'     => 'resource_type',
    ];

    protected $castHandlers = [
        'path' => \App\Entities\Cast\PathCast::class,
    ];

    public function isLink() : bool
    {
        return $this->type == 'link' || ($this->path && substr($this->path, 0, 4) === 'http');
    }

    public function isThumbnail() : bool
    {
        return $this->type == 'thumbnail';
    }

    public function isAsset() : bool
    {
        return $this->path && substr($this->path, 0, strlen(ASSET_PREFIX)) === ASSET_PREFIX;
    }

    public function isTemporary() : bool
    {
        return $this->tmp_path && substr($this->tmp_path, 0, strlen(TEMP_PREFIX)) === TEMP_PREFIX;
    }

    public function isAssigned() : bool
    {
        return !(
            $this->parentId <= 0 ||
            $this->isAsset() ||
            $this->isTemporary()
        );
    }

    public function getURL() : string
    {
        return base_url($this->getPrefix() . ($this->isTemporary()
            ? $this->tmpPath
            : $this->path
        ));
    }

    public function getRootPath() : string
    {
        if ($this->isLink()) {
            throw new \Exception('Links are not children of root!');
        }
        return $this->getPrefix() . $this->path;    }

    private function getPrefix()
    {
        return $this->isAssigned() && !$this->isLink()
            ? SAVE_PREFIX . $this->parentId . UNIX_SEPARATOR
            : '';
    }

    public static function getDefaultImage()
    {
        return new Resource([
            'path' => model(ConfigModel::class)->find('default_image')->value,
            'type' => 'thumbnail',
        ]);
    }
}
