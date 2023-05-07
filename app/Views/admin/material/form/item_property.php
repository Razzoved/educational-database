<?php
    /**
     * Renders one file as a small removable box
     *
     * @param int $id           current index of the file (in given context)
     * @param string $value     file's original name
     */
    $id = $id ?? '@id@';
    $value = $value ?? '@value@';
?>
<div class="property" id="<?= $id ?>">
    <input type="hidden" name="properties[]" value='<?= $id ?>'>
    <p class="property__value"><?= $value ?></p>
    <button class="property__delete">&#10005;</button>
</div>