<?php declare(strict_types=1);

namespace App\Persistence\Entities;

use CodeIgniter\Entity\Entity;

final class View extends Entity
{
    /* DB MAPPING */

    public const string ID = 'id';
    public const string PARENT = 'material_id';
    public const string COUNT = 'material_views';
    public const string CREATED = 'created_at';
    public const string UPDATED = '';
    public const string DELETED = '';

    /* ENTITY */

    protected $attributes = [
        self::ID => null,
        self::PARENT => null,
        self::COUNT => null,
        self::CREATED => null,
    ];

    protected $casts = [
        self::ID => 'int',
        self::PARENT => 'int',
        self::COUNT => 'int',
    ];

    protected $datamap = [
        'parentId' => self::PARENT,
        'count' => self::COUNT,
        'createdDate' => self::CREATED,
    ];
}
