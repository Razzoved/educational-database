<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Material;

class Resource
{
    public function getResources(int $id) : void
    {
        // TODO
    }

    public function getLinks(Material $material) : string
    {
        if (!isset($material)) return 'ERROR';

        $resources = array();

        foreach ($material->resources as $r) {
            if ($r->type == 'link') $resources[] = $r;
        }

        return view('components/resources_as_links', ['resources' => $resources, 'title' => 'Attached links']);
    }

    public function getFiles(Material $material) : string
    {
        if (!isset($material)) return 'ERROR';

        $resources = array();

        foreach ($material->resources as $r) {
            if ($r->type != 'link') $resources[] = $r;
        }

        return view('components/resources_as_links', ['resources' => $resources, 'title' => 'Downloadable files']);
    }
}
