<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Property extends Entity
{
    protected $attributes = [
        'property_id'    => null,
        'property_tag'   => null,
        'property_value' => null,
    ];

    protected $datamap = [
        'id'    => 'property_id',
        'tag'   => 'property_tag',
        'value' => 'property_value',
    ];

    protected $casts = [
        'id'  => 'int',
        'tag' => 'string',
    ];
}
