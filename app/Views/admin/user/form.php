<?php
    /**
     * MODAL: User editor.
     *
     * @param int $id       id of the user, OPTIONAL
     * @param string $name  name of the user, OPTIONAL
     * @param string $email email of the user, OPTIONAL
     */

    $title = isset($id) && $id !== 0 ? 'Update user' : 'New user';
    $submit = isset($id) && $id !== 0 ? 'Edit' : 'Create';

    $id = $id ?? "";
    $name = $name ?? "";
    $email = $email ?? "";
?>

<div class="modal" id="user-window">

    <div class="modal__content">

        <div class="modal__header">
            <h1 class="modal__title"><?= $title ?></h1>
            <span class="modal__close" onclick="modalClose('user-window')">&#10005</span>
        </div>

        <div class="modal__body">
            <form class="form" method="post" action="<?= url_to('Admin\User::save')?>">
                <input type="hidden" id="id" name="id" value="<?= $id ?>">

                <label class="form__label" for="name">Username</label>
                <input class="form__input"
                    type="text"
                    id="name"
                    name="name"
                    value="<?= $name ?>"
                    placeholder="Enter username">

                <label class="form__label" for="email">Email</label>
                <input class="form__input"
                    type="email"
                    id="email"
                    name="email"
                    value="<?= $email ?>"
                    placeholder="name@example.com">

                <fieldset class="form__group">
                    <small id="pass-notice" hidden='true'>Password will not change if left empty:</small>

                    <label class="form__label" for="password">Password</label>
                    <input class="form__input"
                        type="password"
                        id="password"
                        name="password"
                        placeholder="**********">

                    <label class="form__label" for="pass-confirm">Confirm password</label>
                    <input class="form__input"
                        type="password"
                        id="confirm-password"
                        name="confirm-password"
                        placeholder="**********">
                </fieldset>
            </form>
        </div>

        <div class="modal__footer">
            <div class="modal__button-group">
                <button type="button" class="modal__button modal__button--submit" onclick="userSubmit()"><?= $submit ?></button>
                <button type="button" class="modal__button modal__button--cancel" onclick="modalClose('user-window')">Cancel</button>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        <?= include_once(FCPATH . 'js/modal.js') ?>
    </script>
</div>
