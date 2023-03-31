<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use DateTime;

class Material extends Entity
{
    protected $attributes = [
        'material_id'           => null,
        'material_author'       => null,
        'material_blame'        => null,
        'material_status'       => null,
        'material_title'        => null,
        'material_content'      => null,
        'material_views'        => null,
        'material_rating'       => null,
        'material_rating_count' => null,
        'created_at'            => null,
        'updated_at'            => null,
        'related'               => null, // not part of table in DB
        'properties'            => null, // not part of table in DB
        'resources'             => null, // not part of table in DB
    ];

    protected $casts = [
        'material_id'           => 'int',
        'material_author'       => 'string',
        'material_blame'        => 'int',
        'material_status'       => 'statusCast',
        'material_title'        => 'string',
        'material_content'      => 'string',
        'material_views'        => 'int',
        'material_rating'       => 'float',
        'material_rating_count' => 'int',
        'related'               => 'array', // not part of table in DB
        'properties'            => 'array', // not part of table in DB
        'resources'             => 'array', // not part of table in DB
    ];

    protected $castHandlers = [
        'statusCast' => \App\Entities\Cast\StatusCast::class,
    ];

    protected $datamap = [
        'id'           => 'material_id',
        'author'       => 'material_author',
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
            $result[$p->tag][] = $p->value;
        }
        return $result;
    }

    public function createdToDate() : string
    {
        return $this->created_at ? date_format($this->created_at, "d.m.Y") : "";
    }

    public function updatedToDate() : string
    {
        return $this->updated_at ? date_format($this->updated_at, "d.m.Y") : "";
    }

    public function sinceLastUpdate() : string
    {
        if (is_null($this->updatedAt)) {
            return 'No updates';
        }

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

    public function getThumbnail() : Resource
    {
        foreach ($this->resources as $r) {
            if ($r->type === 'thumbnail') {
                return $r;
            };
        }
        return Resource::getMissing();
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
