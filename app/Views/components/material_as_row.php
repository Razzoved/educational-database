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
<div id="<?= $material->id ?>" class="row p-2 g-0 border rounded <?= $material->status === StatusCast::PUBLIC ? ($index % 2 == 1 ? '' : 'bg-light') : 'bg-info' ?>" style="align-items:center; text-align: center;">
    <p class="col-1 me-1"><?= $material->id ?></p>
    <img class="col-1 rounded me-1" src="<?= $material->getThumbnail()->getPath() ?>" style="width: 6rem; height: 6rem; object-fit: scale-down" alt="missing_img">
    <p class="col-3 me-1" style="word-break: break-word;"><?= $material->title?></p>
    <p class="d-none d-xl-inline col-1 me-1">
        <small style="opacity: 0.7">Created at:</small><br><?= $material->createdToDate() ?>
    </p>
    <p class="col-2 me-1"><small style="opacity: 0.7">Last update:</small><br><?= $material->updatedToDate() ?></p>
    <p class="col-2 me-1"><small style="opacity: 0.7">Views:</small><br><?= $material->views ?></p>
    <p class="col-1 me-1"><small style="opacity: 0.7">Rating:</small><br><?= $material->rating ?></p>

    <?php if (isset($showButtons) && $showButtons == true) : ?>
    <div class="btn-group" style="margin-left: auto; width: fit-content; height:100%">
            <a class="btn btn-secondary" target="_blank" style="width: 4rem" href="<?= base_url('admin/materials/preview/' . $material->id) ?>">View</a>
            <a class="btn btn-dark" style="width: 4rem" href="<?= base_url('admin/materials/edit/' . $material->id) ?>">Edit</a>
            <button type="button" class="btn btn-danger" style="width: 3rem" onclick="deleteOpen(<?= $material->id ?>)">&#10005</button>
    </div>
    <?php endif; ?>
</div>
