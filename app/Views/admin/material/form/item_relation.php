<?php

/**
 * Renders one relation as a removable item.
 *
 * @param int $id       id of the related material
 * @param string $title title of the related material
 */
$id = $id ?? '@id@';
$title = $title ?? '@title@';
$url = $url ?? '@url@';
?>
<div class="form__group form__group--horizontal-flex" id="relation<?= $id ?>">
    <input type="hidden" name="relation[<?= $id ?>]" value="<?= esc($title) ?>" required>
    <a class="form__input" target="_blank" href="<?= $url ?>"><?= $title ?></a>
    <button class="form__button form__button--red" type="button" onclick="this.parentElement.remove()" ?>
        &#10005;
    </button>
</div>