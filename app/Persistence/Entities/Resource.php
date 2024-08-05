<?php declare(strict_types=1);

namespace App\Persistence\Entities;

use App\Persistence\Casts\PathCast;
use CodeIgniter\Entity\Entity;

final class Resource extends Entity
{
    /* DB MAPPING */

    public const string ID = 'id';
    public const string PARENT = 'material_id';
    public const string PATH = 'path';
    public const string TYPE = 'type';
    public const string CREATED = 'created_at';
    public const string UPDATED = 'updated_at';
    public const string DELETED = 'deleted_at';

    /* ENTITY */

    protected $attributes = [
        self::ID => null,
        self::PARENT => null,
        self::PATH => null,
        self::TYPE => null,
        self::CREATED => null,
        self::UPDATED => null,
        self::DELETED => null,
        // non-DB attributes
        'tmp_path' => null,
    ];

    protected $casts = [
        self::ID => 'int',
        self::PARENT => 'int',
        self::PATH => 'path',
        self::TYPE => 'string',
    ];

    protected $datamap = [
        'parentId' => self::PARENT,
        'createdDate' => self::CREATED,
        'updatedDate' => self::UPDATED,
        'deletedDate' => self::DELETED,
        'tmpPrefix' => 'tmp_path',
    ];

    protected $castHandlers = [
        'path' => PathCast::class,
    ];
}
