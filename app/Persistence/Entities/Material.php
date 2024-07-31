<?php declare(strict_types=1);

namespace App\Persistence\Entities;

use App\Persistence\Casts\StatusCast;
use CodeIgniter\Entity\Entity;

final class Material extends Entity
{
    protected $attributes = [
        'id' => null,
        'user_id' => null,
        'status' => null,
        'title' => null,
        'content' => null,
        'views' => null,
        'rating' => null,
        'rating_count' => null,
        'published_at' => null,
        'updated_at' => null,
        // non-DB attributes
        'related' => null,
        'properties' => null,
        'resources' => null,
    ];

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'status' => 'status',
        'title' => 'string',
        'content' => 'string',
        'views' => 'int',
        'rating' => 'float',
        'rating_count' => 'int',
        'published_at' => 'datetime',
        'updated_at' => 'datetime',
        // non-DB attributes
        'related' => 'array',
        'properties' => 'array',
        'resources' => 'array',
    ];

    protected $castHandlers = [
        'status' => StatusCast::class,
    ];

    protected $datamap = [
        'blame' => 'user_id',
        'ratingCount' => 'rating_count',
        'releaseDate' => 'published_at',
        'changeDate' => 'updated_at',
    ];
}
