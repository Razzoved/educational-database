<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Post extends Entity
{
    protected $attributes = [
        'post_id'         => null,
        'post_title'      => null,
        'post_thumbnail'  => null,
        'post_type'       => null,
        'post_content'    => null,
        'post_is_public'  => null,
        'post_views'      => null,
        'post_rating'     => null,
        'post_created_at' => null,
        'post_updated_at' => null,
        'properties'      => null, // not part of table in database
    ];

    protected $casts = [
        'post_id'         => 'int',
        'post_title'      => 'string',
        'post_thumbnail'  => 'string',
        'post_type'       => 'int',
        'post_content'    => 'string',
        'post_is_public'  => 'boolean',
        'post_views'      => 'int',
        'post_rating'     => 'int',
    ];

    public function getGroupedProperties() : array
    {
        $result = array();
        foreach ($this->properties as $p) {
            $result[$p->property_tag][] = $p->property_value;
        }
        return $result;
    }
}
