<?php
/* TEMPLATE: shows a single user as a row in administration panel. */

/**
 * @var int $id id of the user
 */
$id = $id ?? '@id@';

/**
 * @var string $name name of the user
 */
$name = $name ?? '@name@';

/**
 * @var string $email email address of the user
 */
$email = $email ?? '@email@';
?>

<div id="<?= $id ?>" class="item">
    <div class="item__header">
        <h2 class="item__title" data-value="name"><?= $name ?></h2>
        <div class="item__controls">
            <button type="button" class="item__edit" onclick="userOpen('<?= $id ?>')">Edit</button>
            <button type="button" class="item__delete" onclick="deleteOpen('<?= $id ?>')">&#10005</button>
        </div>
    </div>
    <p class="item__text" data-value="email"><?= $email ?></p>
</div>