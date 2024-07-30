<?php

declare(strict_types=1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $attributes = [
        'id'       => null,
        'name'     => null,
        'email'    => null,
        'password' => null,
    ];

    protected $casts = [
        'id'       => 'int',
        'name'     => 'string',
        'email'    => 'string',
        'password' => 'string',
    ];
}
