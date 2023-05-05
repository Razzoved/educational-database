<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Property extends Entity
{
    protected $attributes = [
        'property_id'          => null,
        'property_tag'         => null,
        'property_value'       => null,
        'property_priority'    => null,
        'property_description' => null,
        'category'             => null, // not part of db, but loaded
        'children'             => null, // not part of db (on demand)
        'usage'                => null, // not part of db (on demand)
    ];

    protected $casts = [
        'property_id'          => 'int',
        'property_priority'    => 'int',
        'property_description' => 'string',
        'category'             => 'string',
        'children'             => 'array',
        'usage'                => 'int',
    ];

    protected $datamap = [
        'id'          => 'property_id',
        'tag'         => 'property_tag',
        'value'       => 'property_value',
        'priority'    => 'property_priority',
        'description' => 'property_description',
    ];
}
