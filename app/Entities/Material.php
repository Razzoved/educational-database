<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Material extends Entity
{
    protected $attributes = [
        'material_id'    => null,
        'post_id'        => null,
        'material_title' => null,
        'material_type'  => null,
        'created_at'     => null,
        'updated_at'     => null,
        'deleted_at'     => null,
    ];

    protected $casts = [
        'material_id'    => 'int',
        'post_id'        => 'int',
        'material_title' => 'string',
        'material_type'  => 'string',
    ];

    public function isLink() : bool
    {
        return $this->material_type == 'link';
    }

    public function getPath() : string
    {
        return ($this->isLink())
            ? $this->material_title
            : '/uploads/' . $this->post_id . '/' . $this->material_title . '.' . $this->material_type;
    }
}
