<?php
    /**
     * Renders one file as a small removable box
     *
     * Expects:
     * @param int $id           current index of the file (in given context)
     * @param string $path      path to the file's current location
     * @param string $value     file's original name
     */
?>

<div class="item item--stretch" id="file-<?= $id ?? "template"?>" data-value="<?= $path ?? '' ?>">
    <div class="item__header">
        <image class="img-fluid rounded edit-mr"
        style="width: 6rem; height: 6rem; object-fit: scale-down"
        src="<?= App\Libraries\Resources::pathToFileURL($path ?? '') ?>"
        alt="No image">
        <div class="item__controls">
            <button class="item__delete" type="button" <?php if (isset($id)) echo "onclick=\"removeFile('file-$id')\"" ?>>
                &#10005
            </button>
        </div>
    </div>
    <input name="files[<?= $path ?? '' ?>]"
            type="text"
            class="form__input"
            style="width: 100%"
            value="<?= $value ?? '' ?>"
            readonly
            required>
</div>
