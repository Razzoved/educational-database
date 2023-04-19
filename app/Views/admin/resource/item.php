<?php
    /**
     * Template for displaying resource as a row in administration.
     *
     * @var \App\Entities\Resource $resource non null object to display
     */

    $tmp = explode('/', $resource->path);
    $name = end($tmp);
?>
<!-- MATERIAL DISPLAYED AS AN EDITABLE ROW -->
<div id="<?= $resource->path ?>" class="item">
    <div class="item__header">
        <image class="item__logo"
            src="<?= \App\Libraries\Resources::pathToFileURL($resource->getRootPath()) ?>">
        <div class="item__controls">
            <button type="button" class="item__edit" onclick="resourceOpen('<?= $resource->path ?>')">
                Assign
            </button>
            <button type="button" class="item__delete" onclick="deleteOpen('<?= $resource->path ?>')">
                &#10005;
            </button>
        </div>
    </div>
    <h2 class="item__title"><?= $name ?></h3>
</div>
