<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Config extends Entity
{
    protected $attributes = [
        'config_id'     => null,
        'config_value'  => null,
    ];

    protected $casts = [
        'config_id'    => 'string',
        'config_value' => 'string',
    ];

    protected $datamap = [
        'id'    => 'config_id',
        'value' => 'config_value',
    ];
}
