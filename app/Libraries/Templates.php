<?php declare(strict_types = 1);

namespace App\Libraries;

class Templates
{
    public static function wrapHtml(string $html) : string
    {
        return '<div id="template" hidden>' . $html . '</div>';
    }
}
