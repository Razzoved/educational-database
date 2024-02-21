<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Property as EntitiesProperty;
use App\Models\PropertyModel;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;

class Property
{
    /**
     * Loads all filters into an array, separated by AND and OR for separate
     * where clauses.
     *
     * @param CLIRequest|IncomingRequest $request handle to controllers request
     */
    public static function getFilters($request) : array
    {
        $filters = [];

        $and = $request->getGetPost('filter');
        $or = $request->getGetPost('group');

        // echo '<pre>' . print_r($or, true) . '</pre>';

        if ($and) {
            $filters['and'] = is_array($and) ? $and : array($and);
        }
        if ($or) {
            $filters['or'] = $or;
        }

        return $filters;
    }

    public static function treeMap(EntitiesProperty $root, callable $mapper)
    {
        $children = $root->children;
        foreach ($children as $k => $child) {
            $children[$k] = self::treeMap($child, $mapper);
        }
        $root->children = $children;

        return $mapper($root);
    }

    public static function treeForEach(EntitiesProperty $root, callable $callback)
    {
        $callback($root);
        foreach ($root->children as $child) {
            self::treeForEach($child, $callback);
        }
    }

    /**
     * Converts an array of Property objects into a corresponding
     * tree of Property objects.
     *
     * @param array $properties array of valid ids
     *
     * @return array
     */
    public static function getFiltered(array $ids) : array
    {
        $tree = model(PropertyModel::class)->getTree();
        self::filterByIds($tree, $ids);
        return $tree->children;
    }

    /**
     * Prunes the property tree, removing all tags that have
     * no children.
     *
     * @return array
     */
    public static function getCategories() : array
    {
        $tree = model(PropertyModel::class)->getTree();
        self::filterCategories($tree);
        return $tree->children;
    }

    public static function filterByIds(EntitiesProperty &$source, array $valid) : bool
    {
        $children = $source->children;
        foreach ($children as $k => $child) {
            if (!self::filterByIds($child, $valid)) {
                unset($children[$k]);
            }
        }
        $source->children = array_values($children);

        return count($source->children) > 0
            || in_array($source->id, $valid);
    }

    public static function filterCategories(EntitiesProperty &$source) : void
    {
        $children = $source->children;
        foreach ($children as $k => $child) {
            if (empty($child->children)) {
                unset($children[$k]);
            } else {
                self::filterCategories($child);
            }
        }
        $source->children = array_values($children);
    }
}
