<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use DateTime;

class Material extends Entity
{
    protected $attributes = [
        'material_id'           => null,
        'material_blame'        => null,
        'material_status'       => null,
        'material_title'        => null,
        'material_content'      => null,
        'material_views'        => null,
        'material_rating'       => null,
        'material_rating_count' => null,
        'published_at'          => null,
        'updated_at'            => null,
        'related'               => null, // not part of table in DB
        'properties'            => null, // not part of table in DB
        'resources'             => null, // not part of table in DB
    ];

    protected $casts = [
        'material_id'           => 'int',
        'material_blame'        => 'int',
        'material_status'       => 'status',
        'material_title'        => 'string',
        'material_content'      => 'string',
        'material_views'        => 'int',
        'material_rating'       => 'float',
        'material_rating_count' => 'int',
        'published_at'          => 'datetime',
        'related'               => 'array', // not part of table in DB
        'properties'            => 'array', // not part of table in DB
        'resources'             => 'array', // not part of table in DB
    ];

    protected $castHandlers = [
        'status' => \App\Entities\Cast\StatusCast::class,
    ];

    protected $datamap = [
        'id'           => 'material_id',
        'blame'        => 'material_blame',
        'status'       => 'material_status',
        'title'        => 'material_title',
        'content'      => 'material_content',
        'views'        => 'material_views',
        'rating'       => 'material_rating',
        'rating_count' => 'material_rating_count',
    ];

    public function getGroupedProperties() : array
    {
        $result = array();
        foreach ($this->properties as $p) {
            $result[$p->priority][$p->value][] = $p->value;
        }
        return $result;
    }

    public function publishedToDate() : string
    {
        return $this->published_at ? date_format($this->published_at, "d.m.Y") : "NOT PUBLISHED";
    }

    public function updatedToDate() : string
    {
        return $this->updated_at ? date_format($this->updated_at, "d.m.Y") : "ORIGINAL";
    }

    public function sinceLastUpdate() : string
    {
        if (is_null($this->updated_at)) {
            return 'No updates';
        }

        $diff = $this->updated_at->diff(new DateTime('now'));

        if (($t = $diff->format("%m")) > 0)
          $time_ago = $t . ' month' . ($t > 1 ? 's' : '');
        else if (($t = $diff->format("%d")) > 0)
          $time_ago = $t . ' day' . ($t > 1 ? 's' : '');
        else if (($t = $diff->format("%H")) > 0)
          $time_ago = $t . ' hour' . ($t > 1 ? 's' : '');
        else
          $time_ago = 'minute' . ($t > 1 ? 's' : '');

        return $time_ago . ' ago (' . $this->updated_at->format('M j, Y') . ')';
    }

    public function getThumbnail() : Resource
    {
        foreach ($this->resources as $r) {
            if ($r->type === 'thumbnail') {
                return $r;
            };
        }
        return Resource::getDefaultImage();
    }

    public function getLinks() : array
    {
        $links =  $this->resourcesFilter(
            function ($type) { return $type == 'link'; },
            function ($item) { return $item; }
        );
        return $links;
    }

    public function getFiles() : array
    {
        $files = $this->resourcesFilter(
            function ($type) { return $type != 'link' && $type != 'thumbnail'; },
            function ($item) { return $item; }
        );
        return $files;
    }

    private function resourcesFilter(callable $inclusionDecider, callable $converter) : array
    {
        $result = array();
        foreach ($this->resources as $r) {
            if ($inclusionDecider($r->type)) $result[] = $converter($r);
        }
        return $result;
    }
}
