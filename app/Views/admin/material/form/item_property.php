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
<div class="property" id="property<?= $id ?>" onclick="propertyToggle(this)">
    <input type="hidden" name="property[]" value='<?= $id ?>'>
    <p class="property__value"><?= $value ?></p>

    <!-- <div class="property__header"> -->
        <!-- <button class="property__delete">&#10005;</button> -->
    <!-- </div> -->

    <div class="property__children">
        <!-- filled by js -->
    </div>
</div>
