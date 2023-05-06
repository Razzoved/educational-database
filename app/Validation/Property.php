<?php declare(strict_types = 1);

namespace App\Validation;

use App\Entities\Property as EntitiesProperty;

class Property
{
    public function property_value_update(string $value, string $params, array $data, ?string &$error = null) : bool
    {
        $params = explode(',', $params);
        if (sizeof($params) != 2) {
            return false;
        }
        $tag = $params[0];
        $id = $params[1];

        if (!isset($data[$tag]) || !is_numeric($data[$tag])) {
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

    public function valid_tag(int $tag, string $params, array $data, ?string &$error = null) : bool
    {
        $params = explode(',', $params);
        if (sizeof($params) != 1) {
            return false;
        }
        $id = $params[0];

        if (!isset($data[$id]) || !is_numeric($data[$id])) {
            return false;
        }

        $property = model(PropertyModel::class)->getTreeRecursive(
            new EntitiesProperty(['id' => $data[$id]])
        );

        return !$this->checkContains($tag, $property, $error, lang('Validation.valid_tag'));
    }

    protected function checkContains(int $id, EntitiesProperty $property, ?string &$error = null, string $prefix = '') : bool
    {
        if ($property->id === $id) {
            $error = "{$prefix}<br>[{$property->value}]";
            return true;
        }
        foreach ($property->children as $child) {
            $error = "{$prefix}<br>[{$child->value}]";
            if ($this->checkContains($id, $child)) return true;
        }
        return false;
    }
}
