<?php
/* TEMPLATE: shows single resource in administration panel */

/**
 * @var int $id id of the resource
 */
$id = $id ?? '@id@';

/**
 * @var string $path path to thumbnail, may be different than resource path
 */
$path = $path ?? '@path@';

/**
 * @var string $name    name of the resource (usually basename($path))
 */
$name = $name ?? '@name@';
?>

<div id="<?= $id ?>" class="item">
    <div class="item__header">
        <image class="item__logo" src="<?= $path ?>">
            <div class="item__controls">
                <button type="button" class="item__edit" onclick="resourceOpen('<?= $id ?>')">
                    Assign
                </button>
                <button type="button" class="item__delete" onclick="deleteOpen('<?= $id ?>')">
                    &#10005;
                </button>
            </div>
    </div>
    <h2 class="item__title"><?= $name ?></h3>
</div>