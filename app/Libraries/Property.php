<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Property as EntitiesProperty;

class Property
{
    public function getRowTemplate() : string
    {
        return Templates::wrapHtml($this->toRow(new EntitiesProperty()));
    }

    public function toRow(EntitiesProperty $property, int $index = 0) : string
    {
        return view(
            'components/property_as_row',
            ['property' => $property, 'showButtons' => true, 'index' => $index]
        );
    }
}
