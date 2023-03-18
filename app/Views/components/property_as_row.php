<?php
    /**
     * Template for displaying property as a row in administration.
     * May be deleted by a javascript function deleteId!
     *
     * @var \App\Entities\Property $property object of property entity class
     */
    $edit = base_url('admin/tags/edit/' . $property->id);
?>
<!-- MATERIAL DISPLAYED AS AN EDITABLE ROW -->
<div id="<?= $property->id ?>" class="item">
    <div class="item__header">
        <h2 class="item__title" data-value="value"><?= $property->value ?></h2>
        <div class="item__controls">
            <a class="item__edit" href="<?= $edit ?>">
                Edit
            </a>
            <button class="item__delete" type="button" class="delete" onclick="deleteOpen(<?= $property->id ?>)">
                &#10005;
            </button>
        </div>
    </div>
    <div class="item__row">
        <p class="item__text" data-value="tag">
                <?= esc($property->tag) ?>
        </p>
        <p class="item__text" data-value="id">
            <small>ID:</small><br><?= esc($property->id) ?>
        </p>
        <p class="item__text" data-value="usage">
            <small>Usage:</small><br><?= $property->usage ?>
        </p>
    </div>
</div>
