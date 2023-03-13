<?php
    /**
     * Shows all items as clickable buttons. On click it automatically
     * redirects the user to all materials of given property.
     *
     * @param bool $overflow if true, appends overflow tag
     */
?>

<li class="collapsible-list-item<?= $overflow ? ' overflow' : '' ?>">
    <form method="post" action='<?= base_url('/1') ?>' style="display: block">
        <input type="submit"
               name="<?= esc($tag) ?>[<?= esc($value) ?>]" value="<?= esc($value) ?>"
               style="white-space:normal; word-wrap: normal"
        >
    </form>
</li>
