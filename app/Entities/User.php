<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $attributes = [
        'user_name'    => null,
        'user_email'   => null,
        'user_password' => null,
    ];

    protected $casts = [
        'user_name'     => 'string',
        'user_email'    => 'string',
        'user_password' => 'string',
    ];

    protected $datamap = [
        'name'     => 'user_name',
        'email'    => 'user_email',
        'password' => 'user_password',
    ];
}
