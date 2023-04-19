<?php declare(strict_types = 1);

namespace App\Validation;

class Property
{
    public function property_value_update(string $value, int $tag, int $id) : bool
    {
        $properties = model(PropertyModel::class)
            ->where('property_tag', $tag)
            ->where('property_value', $value)
            ->findAll(2);
        $count = array_count_values($properties);
        return $count === 0 || ($count === 1 && isset($id) && $properties[0]->id === $id);
    }
}
