<?php
    /**
     * Shows all items as clickable buttons. On click it automatically
     * redirects the user to all materials of given property.
     */
?>

<form method="get" action='<?= base_url('/1') ?>' style="display: block">
    <input type="hidden"
            name="<?= esc($tag) ?>[<?= esc($value) ?>]"
            value="on">
    <button type="submit"><?= esc($value) ?></button>
</form>
