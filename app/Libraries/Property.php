<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Property as EntitiesProperty;

class Property
{
    public function buttons(string $tag, array $values) : string
    {
        $index = 0;
        $result = '';
        foreach ($values as $value) {
            $result .= $this->getClass($index++);
            $result .= view('components/property_button', ['tag' => $tag, 'value' => $value]);
            $result .= '</li>';
        }

        return $result . $this->getOverflow($index, $tag);
    }

    public function checkboxes(string $tag, array $values) : string
    {
        $index = 0;
        $result = '';
        foreach ($values as $value) {
            $result .= $this->getClass($index++);
            $result .= view('components/property_checkbox', ['tag' => $tag, 'value' => $value]);
            $result .= '</li>';
        }
        return $result . $this->getOverflow($index, $tag);
    }

    public function getRowTemplate() : string
    {
        return Templates::wrapHtml($this->toRow(new EntitiesProperty()));
    }

    public function toRow(EntitiesProperty $property, int $index = 0) : string
    {
        return view(
            'components/property_as_row',
            ['property' => $property, 'showButtons' => true, 'index' => $index]
        );
    }

    private function getClass(int $index) : string
    {
        return '<li class="clps_listitem'
            . ($index >= 6 ? ' clps_overflow' : '')
            . '">';
    }

    private function getOverflow(int $index, string $tag) : string
    {
        return $index > 6
            ? '<li><button id="btn-' . $tag . '" type="button" class="btn btn-link text-primary" style="width: 100%" onclick="toggleOverflow(this)">Show more</button></li>'
            : '';
    }
}
