<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use DateTime;

class Material extends Entity
{
    protected $attributes = [
        'material_id'        => null,
        'material_is_public' => null,
        'material_title'     => null,
        'material_thumbnail' => null,
        'material_type'      => null,
        'material_content'   => null,
        'material_views'     => null,
        'created_at'         => null,
        'updated_at'         => null,
        'rating'             => null, // not part of table in DB
        'rating_count'       => null, // not part of table in DB
        'properties'         => null, // not part of table in DB
        'materials'          => null, // not part of table in DB
    ];

    protected $datamap = [
        'id'        => 'material_id',
        'isPublic'  => 'material_is_public',
        'title'     => 'material_title',
        'thumbnail' => 'material_thumbnail',
        'type'      => 'material_type',
        'content'   => 'material_content',
        'views'     => 'material_views',
    ];

    protected $casts = [
        'id'           => 'int',
        'isPublic'     => 'string',
        'title'        => 'string',
        'thumbnail'    => 'int',
        'content'      => 'string',
        'views'        => 'int',
        'rating'       => 'int',   // not part of table in DB
        'rating_count' => 'int',   // not part of table in DB
        'properties'   => 'array', // not part of table in DB
        'resources'    => 'array', // not part of table in DB
    ];

    public function getGroupedProperties() : array
    {
        $result = array();
        foreach ($this->properties as $p) {
            $result[$p->tag][] = $p->value;
        }
        return $result;
    }

    public function getThumbnail() : string
    {
        return (strncmp($this->thumbnail, "http", 4) == 0)
            ? $this->thumbnail
            : '/uploads/' . $this->id . '/' . $this->thumbnail;
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
