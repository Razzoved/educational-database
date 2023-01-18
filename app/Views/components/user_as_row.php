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
<div id="<?= $user->email ?>" class="row p-2 g-0 border rounded <?= $index % 2 == 1 ? '' : 'bg-light' ?>" style="align-items:center; text-align: center;">
    <p data-value="name" class="col-4 me-1"><?= $user->name ?></p>
    <p data-value="email" class="col-6" style="word-break: break-word;"><?= $user->email ?></p>

    <?php if (isset($showButtons) && $showButtons == true) : ?>
    <div class="btn-group" style="margin-left: auto; width: fit-content; height:100%">
            <button type="button" class="btn btn-dark" style="width: 4rem" onclick="userOpen('<?= $user->email ?>')">Edit</button>
            <button type="button" class="btn btn-danger" style="width: 3rem" onclick="deleteOpen('<?= $user->email ?>')">&#10005</button>
    </div>
    <?php endif; ?>
</div>
