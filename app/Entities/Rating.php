<?php

declare(strict_types=1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Rating extends Entity
{
    protected $attributes = [
        'id'           => null,
        'material_id'  => null,
        'user'         => null,
        'value'        => null,
        'count'        => null, // not a part of db
    ];

    protected $casts = [
        'id'           => 'int',
        'material_id'  => 'int',
        'user'         => 'string',
        'value'        => 'int',
        'count'        => 'int',
    ];

    protected $datamap = [
        'rating_uid'   => 'user',
        'rating_value' => 'value',
    ];
}
