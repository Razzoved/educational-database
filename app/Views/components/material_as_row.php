<?php
    /**
     * Template for displaying material as a row in administration.
     * May be deleted by a javascript function deleteId!
     *
     * @var showButtons boolean value indicating whether to display actions
     * @var material object of material entity class
     */

use App\Entities\Cast\StatusCast;

?>
<div id="<?= $material->id ?>" class="item <?= $material->status === StatusCast::PUBLIC ? ($index % 2 == 1 ? 'public' : 'public-2') : '' ?>">
    <div class="row">
        <img src="<?= $material->getThumbnail()->getPath() ?>" alt="missing_img">
        <h2><?= $material->title?></h2>
    </div>
    <div class="row">
        <p><small>Created at:</small><br><?= $material->createdToDate() ?></p>
        <p><small>Last update:</small><br><?= $material->updatedToDate() ?></p>
        <p><small>ID:</small><br><strong><?= $material->id ?></strong></p>
    </div>
    <div class="row">
        <p><small>Views:</small><br><?= $material->views ?></p>
        <p><small>Rating:</small><br><?= $material->rating ?></p>
    </div>
    <?php if (isset($showButtons) && $showButtons == true) : ?>
    <div class="controls">
        <button class="delete" type="button" onclick="deleteOpen(<?= $material->id ?>)">&#10005</button>
        <a class="button edit" href="<?= base_url('admin/materials/edit/' . $material->id) ?>">Edit</a>
        <a class="button preview" target="_blank" href="<?= base_url('admin/materials/preview/' . $material->id) ?>">View</a>
    </div>
    <?php endif; ?>
</div>
