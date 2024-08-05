<?php declare(strict_types=1);

namespace App\Persistence\Entities;

use App\Persistence\Casts\PasswordCast;
use CodeIgniter\Entity\Entity;

final class User extends Entity
{
    /* DB MAPPING */

    public const string ID = 'id';
    public const string ALIAS = 'name';
    public const string EMAIL = 'email';
    public const string PASSWORD = 'password';
    public const string CREATED = 'created_at';
    public const string UPDATED = 'updated_at';
    public const string DELETED = '';

    /* ENTITY */

    protected $attributes = [
        self::ID => null,
        self::ALIAS => null,
        self::EMAIL => null,
        self::PASSWORD => null,
        self::CREATED => null,
        self::UPDATED => null,
    ];

    protected $casts = [
        self::ID => 'int',
        self::ALIAS => 'string',
        self::EMAIL => 'string',
        self::PASSWORD => 'password',
    ];

    protected $castHandlers = [
        'password' => PasswordCast::class,
    ];
}
