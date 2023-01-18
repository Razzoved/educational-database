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
<div id="<?= $property->id ?>" class="row p-2 g-0 border rounded <?= $index % 2 == 1 ? '' : 'bg-light' ?>" style="align-items:center; text-align: center;">
    <p data-value="id" class="col-1 me-1"><?= $property->id ?></p>
    <p data-value="tag" class="col-3 me-1" style="word-break: break-word;"><?= $property->tag ?></p>
    <p data-value="value" class="col-3 me-1" style="word-break: break-word;"><?= $property->value ?></p>
    <p data-value="usage" class="col-1 me-1 d-none d-md-inline"><small style="opacity: 0.7">Usage:</small><br><?= $property->usage ?></p>

    <?php if (isset($showButtons) && $showButtons == true) : ?>
    <div class="btn-group" style="margin-left: auto; width: fit-content; height:100%">
            <a class="btn btn-dark" style="width: 4rem" href="<?= base_url('admin/tags/edit/' . $property->id) ?>">Edit</a>
            <button type="button" class="btn btn-danger" style="width: 3rem" onclick="deleteOpen(<?= $property->id ?>)">&#10005</button>
    </div>
    <?php endif; ?>
</div>
