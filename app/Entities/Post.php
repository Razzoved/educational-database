<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use DateTime;

class Post extends Entity
{
    protected $attributes = [
        'post_id'         => null,
        'post_is_public'  => null,
        'post_title'      => null,
        'post_thumbnail'  => null,
        'post_type'       => null,
        'post_content'    => null,
        'post_views'      => null,
        'created_at'      => null,
        'updated_at'      => null,
        'rating'          => null, // not part of table in DB
        'rating_count'    => null, // not part of table in DB
        'properties'      => null, // not part of table in DB
        'materials'       => null, // not part of table in DB
    ];

    protected $casts = [
        'post_id'         => 'int',
        'post_title'      => 'string',
        'post_thumbnail'  => 'string',
        'post_type'       => 'int',
        'post_content'    => 'string',
        'post_is_public'  => 'boolean',
        'post_views'      => 'int',
        'rating'          => 'int',   // not part of table in DB
        'rating_count'    => 'int',   // not part of table in DB
        'properties'      => 'array', // not part of table in DB
        'materials'       => 'array', // not part of table in DB
    ];

    public function getGroupedProperties() : array
    {
        $result = array();
        foreach ($this->properties as $p) {
            $result[$p->property_tag][] = $p->property_value;
        }
        return $result;
    }

    public function createdToDate() : string
    {
        return date_format($this->created_at, "d.m.Y");
    }

    public function updatedToDate() : string
    {
        return date_format($this->updated_at, "d.m.Y");
    }

    function sinceLastUpdate() {
        $time_ago = '';
        $diff = $this->updated_at->diff(new DateTime('now'));

        if (($t = $diff->format("%m")) > 0)
          $time_ago = $t . ' months';
        else if (($t = $diff->format("%d")) > 0)
          $time_ago = $t . ' days';
        else if (($t = $diff->format("%H")) > 0)
          $time_ago = $t . ' hours';
        else
          $time_ago = 'minutes';

        return $time_ago . ' ago (' . $this->updated_at->format('M j, Y') . ')';
    }
}
