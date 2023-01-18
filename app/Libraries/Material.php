<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Material as EntitiesMaterial;

class Material
{
    public function toCard(EntitiesMaterial $material) : string
    {
        return view(
            'components/material_as_card',
            ['material' => $material]
        );
    }

    public function getRowTemplate() : string
    {
        return Templates::wrapHtml($this->toRow(new EntitiesMaterial()));
    }

    public function toRow(EntitiesMaterial $material, int $index = 0) : string
    {
        return view(
            'components/material_as_row',
            ['material' => $material, 'showButtons' => true, 'index' => $index]
        );
    }

    public function listLinks(EntitiesMaterial $material) : string
    {
        if (!isset($material)) return 'ERROR';

        return view(
            'components/resources_as_links',
            ['resources' => $material->getLinks(), 'title' => 'Attached links']
        );
    }

    public function listFiles(EntitiesMaterial $material) : string
    {
        if (!isset($material)) return 'ERROR';

        return view(
            'components/resources_as_links',
            ['resources' => $material->getFiles(), 'title' => 'Downloadable files']
        );
    }
}
