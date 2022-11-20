<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Material as EntitiesMaterial;

class Material
{
    public function toCard(EntitiesMaterial $material) : string
    {
        return view('components/material_as_card', ['material' => $material]);
    }

    public function getLinks(EntitiesMaterial $material) : string
    {
        if (!isset($material)) return 'ERROR';

        $resources = array();

        foreach ($material->resources as $r) {
            if ($r->type == 'link') $resources[] = $r;
        }

        return view('components/resources_as_links', ['resources' => $resources, 'title' => 'Attached links']);
    }

    public function getFiles(EntitiesMaterial $material) : string
    {
        if (!isset($material)) return 'ERROR';

        $resources = array();

        foreach ($material->resources as $r) {
            if ($r->type != 'link') $resources[] = $r;
        }

        return view('components/resources_as_links', ['resources' => $resources, 'title' => 'Downloadable files']);
    }
}
