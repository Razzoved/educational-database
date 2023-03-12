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

<div id="file-<?= $id ?? "template"?>" class="row g-0 border p-2 rounded" style="margin-top: 1rem" data-value="<?= $path ?? '' ?>">
    <div class="col-auto">
        <image class="img-fluid rounded edit-mr"
        style="width: 6rem; height: 6rem; object-fit: scale-down"
        src="<?= App\Libraries\Resources::pathToFileURL($path ?? '') ?>"
        alt="No image">
    </div>
    <div class="col">
        <input name="files[<?= $path ?? '' ?>]"
                type="text"
                class="form-control"
                value="<?= $value ?? '' ?>"
                <?php if (!isset($readonly) || $readonly === true) echo 'readonly' ?>
                required>
        <button type="button" <?php if (isset($id)) echo "onclick=\"removeFile('file-$id')\"" ?> class="btn" style="font-weight: bold; float: right">&#10005</button>
    </div>
</div>
