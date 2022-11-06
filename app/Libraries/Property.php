<?php declare(strict_types = 1);

namespace App\Libraries;

use CodeIgniter\View\View;

class Property
{
    public function buttons(string $tag, array $values) : string
    {
        $result = '';
        foreach ($values as $value) {
            $result .= '<li class="mb-1">';
            $result .= view('components/property_button', ['tag' => $tag, 'value' => $value]);
            $result .= '</li>';
        }
        return $result;
    }

    public function checkboxes(string $tag, array $values) : string
    {
        $result = '';
        foreach ($values as $value) {
            $result .= '<li class="list-group-item">';
            $result .= view('components/property_checkbox', ['tag' => $tag, 'value' => $value]);
            $result .= '</li>';
        }
        return $result;
    }
}
