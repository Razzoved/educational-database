<?php declare(strict_types = 1);

namespace App\Libraries;

class Property
{
    public function postFilters(array $orderedProperties) : string
    {
        $retVal = "";
        $last = null;
        foreach ($orderedProperties as $p) {
            if ($p->property_tag != $last && $last != null) {
                $retVal .= '</ul>';
                $retVal .= '<hr>';
            }
            if ($p->property_tag != $last) {
                $retVal .= '<ul>';
                $retVal .= "<h6>$p->property_tag</h6>";
                $retVal .= view('components/property_as_checkbox', ['filter' => $p]);
                $last = $p->property_tag;
            } else {
                $retVal .= view('components/property_as_checkbox', ['filter' => $p]);
            }
        }
        return $retVal;
    }

    public function postGroup(string $tag, array $values) : string
    {
        return view('components/property_as_list', ['tag' => $tag, 'values' => $values]);
    }
}
