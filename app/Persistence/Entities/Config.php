<?php declare(strict_types=1);

namespace App\Persistence\Entities;

use CodeIgniter\Entity\Entity;

final class Config extends Entity
{
    /* DB MAPPING */

    public const string ID = 'id'; 
    public const string VALUE = 'value';

    /* ENTITY */

    protected $attributes = [
        self::ID => null,
        self::VALUE => null,
    ];

    protected $casts = [
        self::ID => 'string',
        self::VALUE => 'string',
    ];
}
