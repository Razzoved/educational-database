<?php
    /**
     * Template for displaying property as a row in administration.
     * May be deleted by a javascript function deleteId!
     *
     * @var showButtons boolean value indicating whether to display actions
     * @var property object of property entity class
     */
?>
<!-- MATERIAL DISPLAYED AS AN EDITABLE ROW -->
<div id="<?= $property->id ?>" class="item <?= $index % 2 == 1 ? 'public' : 'public-2' ?>">
    <div class="row">
        <p data-value="id"><small>ID:</small><br><?= $property->id ?></p>
        <h2 data-value="value"><?= $property->value ?></h2>
    </div>
    <div class="row">
        <p data-value="usage"><small>Usage:</small><br><?= $property->usage ?></p>
        <p data-value="tag"><?= $property->tag ?></p>
    </div>

    <?php if (isset($showButtons) && $showButtons == true) : ?>
    <div class="controls">
        <button type="button" class="delete" onclick="deleteOpen(<?= $property->id ?>)">&#10005</button>
        <a class="button edit" href="<?= base_url('admin/tags/edit/' . $property->id) ?>">Edit</a>
    </div>
    <?php endif; ?>
</div>
