<?php
    /**
     * Template for displaying user as a row in administration.
     * May be deleted by a javascript function deleteId!
     *
     * @var \App\Entities\User $user object of user entity class
     */
?>
<!-- MATERIAL DISPLAYED AS AN EDITABLE ROW -->
<div id="<?= $user->email ?>" class="item">
    <div class="item__header">
        <h2 class="item__title" data-value="name"><?= $user->name ?></h2>
        <div class="item__controls">
            <button type="button" class="item__edit" onclick="userOpen('<?= $user->email ?>')">Edit</button>
            <button type="button" class="item__delete" onclick="deleteOpen('<?= $user->email ?>')">&#10005</button>
        </div>
    </div>
    <p class="item__text" data-value="email"><?= $user->email ?></p>
</div>
