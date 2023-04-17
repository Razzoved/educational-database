<?php
    /**
     * Template for displaying property as a row in administration.
     * May be deleted by a javascript function deleteId!
     *
     * @var \App\Entities\Property $property object of property entity class
     */
    $edit = url_to('Admin\Property::get', $property->id);
?>
<!-- MATERIAL DISPLAYED AS AN EDITABLE ROW -->
<div id="<?= $property->id ?>" class="tooltip">
    <div class="item">
        <div class="item__header">
            <h2 class="item__title" data-value="value"><?= $property->value ?></h2>
            <div class="item__controls">
                <button class="item__edit" type="button" onclick="propertyOpen(<?= $property->id ?>)">
                    Edit
                </button>
                <button class="item__delete" type="button" onclick="deleteOpen(<?= $property->id ?>)">
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
    <?php if ($property->description !== '') : ?>
        <span class="tooltip__text"><?= esc($property->description) ?></span>
    <?php endif; ?>
</div>
