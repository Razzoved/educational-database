<?php declare(strict_types=1);

namespace App\Persistence\Entities;

use CodeIgniter\Entity\Entity;

final class Config extends Entity
{
    protected $attributes = [
        'id' => null,
        'value' => null,
    ];

    protected $casts = [
        'id' => 'string',
        'value' => 'string',
    ];
}
