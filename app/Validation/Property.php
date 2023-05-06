<?php declare(strict_types = 1);

namespace App\Validation;

class Property
{
    public function property_value_update(string $value, string $params, array $data, ?string &$error = null) : bool
    {
        if (!isset($value)) {
            $error = 'Missing value!';
            return false;
        }

        $params = explode(',', $params);
        if (sizeof($params) != 2) {
            $error = 'Invalid parameters for property update, should be - tag, id!';
            return false;
        }

        $tag = $params[0];
        $id = $params[1];

        if (!isset($data[$tag])) {
            $error = 'Missing id of category!';
            return false;
        }

        if (!is_numeric($data[$tag])) {
            $error = strtoupper($tag) . ' must be a number!';
            return false;
        }

        $properties = model(PropertyModel::class)
            ->where('property_tag', (int) $data[$tag])
            ->where('property_value', $value)
            ->findAll(2);
        $count = sizeof($properties);

        return $count === 0 || (
            isset($data[$id]) &&
            is_numeric($data[$id]) &&
            $count === 1 &&
            $properties[0]->id === (int) $data[$id]
        );
    }
}
