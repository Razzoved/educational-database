<div id="file-<?= $id ?? "template"?>" class="row g-0 border p-2 rounded" style="margin-top: 1rem" <?php

if (!isset($hidden) || $hidden === true) echo 'hidden'?>>
    <div class="col-auto">
        <image class="img-fluid rounded edit-mr"
        style="width: 6rem; height: 6rem; object-fit: scale-down"
        src="<?php
            use App\Entities\Resource;
            echo base_url() . '/' . ($image ?? Resource::NO_THUMBNAIL_PATH)
            ?>"
        alt="No image">
    </div>
    <div class="col">
        <input name="files[<?= $path ?? 'fallback' ?>]"
                type="text"
                class="form-control"
                value="<?= $value ?? '' ?>"
                <?php if (!isset($readonly) || $readonly === true) echo 'readonly' ?>
                <?php if (!isset($hidden) || $hidden === true) echo 'disabled' ?>
                required>
        <button type="button" <?php if (isset($id)) echo "onclick=\"removeFile('file-$id','" . ($path ?? '') . "')\"" ?> class="btn" style="font-weight: bold; float: right">&#10005</button>
    </div>
</div>
