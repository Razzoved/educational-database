<?php declare(strict_types=1);

namespace App\Persistence\Entities;

use App\Persistence\Casts\PathCast;
use CodeIgniter\Entity\Entity;

final class Resource extends Entity
{
    protected $attributes = [
        'id' => null,
        'material_id' => null,
        'path' => null,
        'type' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null,
        // non-DB attributes
        'tmp_path' => null,
    ];

    protected $casts = [
        'id' => 'int',
        'material_id' => 'int',
        'path' => 'path',
        'type' => 'path',
    ];

    protected $datamap = [
        'parentId' => 'material_id',
    ];

    protected $castHandlers = [
        'path' => PathCast::class,
    ];
}
