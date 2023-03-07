<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Rating extends Entity
{
    protected $attributes = [
        'id'           => null,
        'material_id'  => null,
        'rating_uid'   => null,
        'rating_value' => null,
    ];

    protected $casts = [
        'id'           => 'int',
        'material_id'  => 'int',
        'rating_uid'   => 'string',
        'rating_value' => 'int',
    ];
}
