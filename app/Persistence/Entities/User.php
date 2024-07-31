<?php declare(strict_types=1);

namespace App\Persistence\Entities;

use App\Persistence\Casts\PasswordCast;
use CodeIgniter\Entity\Entity;

final class User extends Entity
{
    protected $attributes = [
        'id' => null,
        'name' => null,
        'email' => null,
        'password' => null,
    ];

    protected $casts = [
        'id' => 'int',
        'name' => 'string',
        'email' => 'string',
        'password' => 'password',
    ];

    protected $castHandlers = [
        'password' => PasswordCast::class,
    ];
}
