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
        return $this->type == 'link';
    }

    public function isThumbnail() : bool
    {
        return $this->type == 'thumbnail';
    }

    public function getName(bool $fileExtension = true) : string
    {
        if (!$fileExtension) {
            $p = explode('.', $this->path);
            array_pop($p);
            return join('.', $p);
        }
        return $this->path;
    }

    public function getPath(bool $src = true) : string
    {
        $path = $this->path;

        if (!$this->isLink()) {
            $path = $src ? (base_url() . '/') : '';
            $path .= isset($this->parentId) ? ('public/uploads/' . $this->parentId . '/') : '';
            $path .= $this->getName();
        }

        return $path;
    }

    public function getFileThumbnail() : Resource
    {
        return Resource::strToFileThumbnail($this->getPath(false));
    }

    public static function ignore(?string $path) : bool
    {
        return $path === null || $path === 'assets/missing.png' || $path === base_url('public/assets/missing.png');
    }

    public static function strToThumbnail(?string $path) : Resource
    {
        $asset = 'public/assets/missing.png';

        if ($path && file_exists(ROOTPATH . $path)) {
            $asset = $path;
        }

        return new Resource([
            'resource_path' => $asset,
            'resource_type' => 'thumbnail',
        ]);
    }

    public static function strToFileThumbnail(?string $path) : Resource
    {
        $prefix = 'public/assets/';
        $asset = 'missing.png';

        if ($path) {
            $splitPath = explode('.', $path);
            $fileType = end($splitPath);
            switch ($fileType) {
                # images
                case 'png':
                case 'jpg':
                case 'jpeg':
                case 'bmp':
                case 'tiff':
                    $prefix = '';
                    $asset = $path;
                    break;
                # other file types
                case 'avi':
                    $asset = 'file_avi.png';
                    break;
                case 'cdr':
                    $asset = 'file_cdr.png';
                    break;
                case 'csv':
                    $asset = 'file_csv.png';
                    break;
                case 'doc':
                case 'docx':
                    $asset = 'file_doc.png';
                    break;
                case 'mp4':
                case 'mp3':
                    $asset = 'file_mp3.png';
                    break;
                case 'pdf':
                    $asset = 'file_pdf.png';
                    break;
                case 'ppt':
                case 'pptx':
                    $asset = 'file_ppt.png';
                    break;
                case 'rar':
                    $asset = 'file_rar.png';
                    break;
                case 'txt':
                    $asset = 'file_txt.png';
                    break;
                case 'xls':
                    $asset = 'file_xls.png';
                    break;
                case 'zip':
                    $asset = 'file_zip.png';
                    break;
                default:
                    break;
            }
        }

        return new Resource([
            'resource_path' => $prefix . $asset,
            'resource_type' => 'thumbnail',
        ]);
    }
}
