<?php declare(strict_types = 1);

namespace App\Libraries;

class Property
{
    /**
     * Returns a view of a properties with given tag. Each checkbox is clickable,
     * but does not call the filter function. Checked values are used on next
     * search.
     *
     * @param string $tag   tag of properties, used as name of group
     * @param array $values property values, used as checkboxes
     */
    public function collapsibleCheckboxes(string $tag, array $values) : string
    {
        $result = "";
        foreach ($values as $value) {
            $result .= view('components/property_as_checkbox', ['tag' => $tag, 'value' => $value]);
        }
        return $result;
    }

    public function checkboxList(array $groupedProperties) : string
    {
        $result = "<form method='post' action='/'>";
        foreach ($groupedProperties as $tag => $values) {
            $result .= $this->collapsibleCheckboxes($tag, $values);
        }
        return $result . "</form>";
    }

    /**
     * Returns a view of a properties with given tag. Each button is clickable
     * and automatically calls the filter function on all posts.
     *
     * @param string $tag   tag of properties, used as name of group
     * @param array $values property values, used as buttons
     */
    public function collapsibleButtons(string $tag, array $values) : string
    {
        $result = "";
        foreach ($values as $value) {
            $result .= view('components/property_as_button', ['tag' => $tag, 'value' => $value]);
        }
        return $result;
    }

    public function buttonList(array $groupedProperties) : string
    {
        $result = "";
        foreach ($groupedProperties as $tag => $values) {
            $result .= $this->collapsibleButtons($tag, $values);
        }
        return $result;
    }
}
