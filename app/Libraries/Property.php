<?php declare(strict_types = 1);

namespace App\Libraries;

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

        if ($and) {
            $filters['and'] = is_array($and) ? $and : array($and);
        }
        if ($or) {
            $filters['or'] = is_array($or) ? $or : array($or);
        }

        return $filters;
    }
}
