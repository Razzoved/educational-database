<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Models\PropertyModel;

class Property
{
    public function postFilters(array $filters) : string {
        $retVal = "";
        $last = null;
        foreach ($filters as $filter) {
            if ($filter->property_tag != $last && $last != null) {
                $retVal .= '</ul>';
                $retVal .= '<hr>';
            }
            if ($filter->property_tag != $last) {
                $retVal .= '<ul>';
                $retVal .= "<h6>$filter->property_tag</h6>";
                $retVal .= view('components/post_filter', ['filter' => $filter]);
                $last = $filter->property_tag;
            } else {
                $retVal .= view('components/post_filter', ['filter' => $filter]);
            }
        }
        return $retVal;
    }
}
