<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Post;
use CodeIgniter\Exceptions\PageNotFoundException;

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
                $retVal .= view('components/post_filter', ['filter' => $p]);
                $last = $p->property_tag;
            } else {
                $retVal .= view('components/post_filter', ['filter' => $p]);
            }
        }
        return $retVal;
    }

    public function postProperties(Post $post) : string
    {
        if (!isset($post) || !isset($post->properties)) throw PageNotFoundException::forPageNotFound();

        $retVal = "";

        foreach ($post->getGroupedProperties() as $group) {
            $retVal .= view('components/post_group', ['group' => $group]);
        }

        return $retVal;
    }
}
