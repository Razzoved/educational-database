<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Material as EntitiesMaterial;

class Material
{
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
}
