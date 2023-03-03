<?php declare(strict_types = 1);

namespace App\Validation;

class Property
{
    public function uniqueProperty(?string $tag, ?string $value) : bool
    {
        return $tag === null || $value === null
            || model(PropertyModel::class)->builder()
                                          ->select('*')
                                          ->where('property_value', $value)
                                          ->where('property_tag', $tag)
                                          ->get(1)
                                          ->getResultArray() === array();
    }
}
