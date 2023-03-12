<?php
    /**
     * Template for displaying resource as a row in administration.
     *
     * @var showButtons boolean value indicating whether to display actions
     * @var resource object of \App\Entities\Resource class
     */

    $tmp = explode('/', $resource->path);
    $name = end($tmp);
?>
<!-- MATERIAL DISPLAYED AS AN EDITABLE ROW -->
<div id="<?= $resource->path ?>" class="item <?= $index % 2 == 1 ? 'public' : 'public-2' ?>">
    <div class="row">
        <image class="image" src="<?= \App\Libraries\Resources::pathToFileURL($resource->getRootPath()) ?>" style="width: 3rem; height: 3rem; object-fit: scale-down; background-color: unset"></image>
        <h3 class="name"><?= $name ?></h3>
    </div>
    <?php if (isset($showButtons) && $showButtons == true) : ?>
    <div class="controls">
        <button type="button" class="delete" style="width: 3rem" onclick="deleteOpen('<?= $resource->path ?>')">&#10005</button>
        <button type="button" class="edit" style="width: 4rem" onclick="resourceOpen('<?= $resource->path ?>')">Assign</button>
    </div>
    <?php endif; ?>
</div>
