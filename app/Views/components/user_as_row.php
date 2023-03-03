<?php
    /**
     * Template for displaying user as a row in administration.
     * May be deleted by a javascript function deleteId!
     *
     * @var showButtons boolean value indicating whether to display actions
     * @var user object of user entity class
     */
?>
<!-- MATERIAL DISPLAYED AS AN EDITABLE ROW -->
<div id="<?= $user->email ?>" class="item <?= $index % 2 == 1 ? 'public' : 'public-2' ?>">
    <div class="row">
        <h2 data-value="name"><?= $user->name ?></h2>
        <p data-value="email"><?= $user->email ?></p>
    </div>
    <?php if (isset($showButtons) && $showButtons == true) : ?>
    <div class="controls">
        <button type="button" class="delete" style="width: 3rem" onclick="deleteOpen('<?= $user->email ?>')">&#10005</button>
        <button type="button" class="edit" style="width: 4rem" onclick="userOpen('<?= $user->email ?>')">Edit</button>
    </div>
    <?php endif; ?>
</div>
