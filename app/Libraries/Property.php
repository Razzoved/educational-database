<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Models\PropertyModel;

class Property
{
    public function postFilters(array $properties) : string {
        $retVal = "";
        $last = null;
        foreach ($properties as $p) {
            if ($p->property_tag != $last && $last != null) {
                $retVal .= '</ul>';
                $retVal .= '<hr>';
            }
            if ($p->property_tag != $last) {
                $retVal .= '<ul>';
                $retVal .= "<h6>$p->property_tag</h6>";
                $retVal .= view('components/post_filter', ['filter' => $p]);
                $last = $p->property_tag;
            } else {
                $retVal .= view('components/post_filter', ['filter' => $p]);
            }
        }
        return $retVal;
    }
}
