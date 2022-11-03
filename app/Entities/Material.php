<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Material extends Entity
{
    protected $attributes = [
        'material_id'         => null,
        'post_id'             => null,
        'material_title'      => null,
        'material_type'       => null,
        'material_path'       => null,
        'material_created_at' => null,
        'material_updated_at' => null,
    ];

    protected $casts = [
        'material_id'    => 'int',
        'post_id'        => 'int',
        'material_title' => 'string',
        'material_type'  => 'string',
        'material_path'  => 'string',
    ];
}
