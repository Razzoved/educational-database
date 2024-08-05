<?php declare(strict_types=1);

namespace App\Persistence\Entities;

use CodeIgniter\Entity\Entity;

final class Property extends Entity
{
    /* DB MAPPING */

    public const string ID = 'id';
    public const string PARENT = 'parent';
    public const string VALUE = 'value';
    public const string PRIORITY = 'priority';
    public const string DESCRIPTION = 'description';

    /* ENTITY */

    protected $attributes = [
        self::ID => null,
        self::PARENT => null,
        self::VALUE => null,
        self::PRIORITY => null,
        self::DESCRIPTION => null,
        // non-DB attributes
        'category' => null,
        'children' => null,
        'usage' => null,
    ];

    protected $casts = [
        self::ID => 'int',
        self::PARENT => 'int',
        self::VALUE => 'string',
        self::PRIORITY => 'int',
        self::DESCRIPTION => 'string',
        'category' => 'string',
        'children' => 'array',
        'usage' => 'int',
    ];
}
