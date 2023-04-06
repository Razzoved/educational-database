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
        return $this->type == 'link' || substr($this->path, 0, 4) === 'http';
    }

    public function isThumbnail() : bool
    {
        return $this->type == 'thumbnail';
    }

    public function isAsset() : bool
    {
        return substr($this->path, 0, strlen(ASSET_PREFIX)) === ASSET_PREFIX
            || substr($this->path, 0, strlen('missing')) === "missing";
    }

    public function isTemporary() : bool
    {
        return $this->tmp_path !== null && substr($this->tmp_path, 0, strlen(TEMP_PREFIX)) === TEMP_PREFIX;
    }

    public function isAssigned() : bool
    {
        return $this->parentId > 0 && !$this->isTemporary();
    }


    public function getName(bool $showExtension = true) : string
    {
        if (!$showExtension) {
            $p = explode('.', $this->path);
            array_pop($p);
            return join('.', $p);
        }
        return $this->path;
    }

    public function getURL() : string
    {
        if ($this->isLink()) {
            return $this->path;
        }

        if ($this->isTemporary()) {
            return base_url($this->tmp_path);
        }

        return base_url($this->getPrefix() . $this->path);
    }

    public function getRootPath() : string
    {
        if ($this->isLink()) {
            throw new \Exception('Resource is of LINK type. Links are not located in subfolders of root');
        }

        return $this->getPrefix() . $this->path;
    }

    private function getPrefix()
    {
        if (!$this->isAsset() && $this->isAssigned()) {
            return SAVE_PREFIX . $this->parentId . '/';
        }
        return '';
    }

    public static function getMissing()
    {
        return new Resource([
            'path' => ASSET_PREFIX . 'missing.png',
            'type' => 'thumbnail',
        ]);
    }
}
