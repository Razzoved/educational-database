<?php declare(strict_types=1);

namespace App\Persistence\Entities;

use CodeIgniter\Entity\Entity;

final class Rating extends Entity
{
    protected $attributes = [
        'id' => null,
        'material_id' => null,
        'user' => null,
        'value' => null,
        'count' => null,  // not a part of db
    ];

    protected $casts = [
        'id' => 'int',
        'material_id' => 'int',
        'user' => 'string',
        'value' => 'int',
        'count' => 'int',
    ];

    protected $datamap = [
        'parentId' => 'material_id',
        'ratingUid' => 'user',
        'ratingValue' => 'value',
    ];
}
