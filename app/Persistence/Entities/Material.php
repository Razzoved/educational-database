<?php declare(strict_types=1);

namespace App\Persistence\Entities;

use App\Persistence\Casts\StatusCast;
use CodeIgniter\Entity\Entity;

final class Material extends Entity
{
    /* DB MAPPING */

    public const string ID = 'id';
    public const string OLD = 'previous_id';
    public const string USER = 'user_id';
    public const string STATUS = 'status';
    public const string TITLE = 'title';
    public const string CONTENT = 'content';
    public const string CREATED = 'created_at';
    public const string UPDATED = 'updated_at';
    public const string DELETED = '';

    /* ENTITY */

    protected $attributes = [
        self::ID => null,
        self::OLD => null,
        self::USER => null,
        self::STATUS => null,
        self::TITLE => null,
        self::CONTENT => null,
        self::CREATED => null,
        self::UPDATED => null,
        // non-DB attributes
        'editor' => null,
        'views' => null,
        'rating' => null,
        'rating_count' => null,
        'related' => null,
        'properties' => null,
        'resources' => null,
    ];

    protected $casts = [
        self::ID => 'int',
        self::OLD => 'int',
        self::USER => 'int',
        self::STATUS => 'status',
        self::TITLE => 'string',
        self::CONTENT => 'string',
        'editor' => User::class,
        'views' => 'int',
        'rating' => 'float',
        'rating_count' => 'int',
        'related' => 'array',
        'properties' => 'array',
        'resources' => 'array',
    ];

    protected $castHandlers = [
        'status' => StatusCast::class,
    ];

    protected $datamap = [
        'previous' => self::OLD,
        'createdDate' => self::CREATED,
        'updatedDate' => self::UPDATED,
        'ratingCount' => 'rating_count',
    ];
}
