<?php
    /**
     * Renders one file as a small removable box
     *
     * @param int $id           current index of the file (in given context)
     * @param string $value     file's original name
     */
    $id = $id ?? '@id@';
    $value = $value ?? '@value@';
    $description = $description ?? '@description@';
?>
<div class="property__item" id="property<?= $id ?>" title="<?= $description ?>">
    <input class="property__input" type="hidden" name="property[]" value='<?= $id ?>'>
    <p class="property__value"><?= $value ?></p>
    <div class="property__children"></div>
</div>
