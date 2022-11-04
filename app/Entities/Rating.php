<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Rating extends Entity
{
    protected $attributes = [
        'post_id'      => null,
        'rating_uid'   => null,
        'rating_value' => null,
    ];

    protected $casts = [
        'post_id'      => 'int',
        'rating_uid'   => 'string',
        'rating_value' => 'int',
    ];
}
