<?php

/**
 * Renders one file as a small removable box
 *
 * @param int $id           current index of the file (in given context)
 * @param string $path      path to the file's current location
 * @param string $value     file's original name
 */
$id = $id ?? '@id@';
$path = $path ?? '@path@';
$value = $value ?? '@value@';
$imageURL = $imageURL ?? '@imageURL@';
?>
<div class="item" id="file<?= $id ?>">

    <div class="item__header">

        <image class="item__logo" style="border-radius: 100%" src="<?= $imageURL ?>" alt="File image">

            <div class="item__controls">
                <button class="item__delete" type="button" onclick="removeFile('file<?= $id ?>')">
                    &#10005;
                </button>
            </div>

    </div>

    <input type="text" class="item__title item__title--stripped" name="file[<?= $path ?>]" value="<?= $value ?>" readonly required>
</div>