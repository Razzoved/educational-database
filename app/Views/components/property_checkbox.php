<?php
    /**
     * Shows all items as clicakble checkboxes. On next search all
     * checked checkboxes are used for filtering.
     *
     * @param bool $overflow if true, appends overflow tag
     */
?>

<li class="collapsible-list-item<?= $overflow ? ' overflow' : '' ?>">
    <input type="checkbox"
        name="filters[<?= esc($tag) ?>][<?= esc($value) ?>]"
        id="filters[<?= esc($tag) ?>][<?= esc($value) ?>]"
        label="checkbox-item">
    </input>
    <label for="filters[<?= esc($tag) ?>][<?= esc($value) ?>]">
            <?= esc($value) ?>
    </label>
</li>
