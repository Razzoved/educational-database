<?php
    /**
     * MODAL: User editor.
     *
     * @param int $id       id of the user, OPTIONAL
     * @param string $name  name of the user, OPTIONAL
     * @param string $email email of the user, OPTIONAL
     */

    $title = $title ?? '@title@';
    $submit = $submit ?? '@submit@';

    $id = $id ?? "@id@";
    $name = $name ?? "@name@";
    $email = $email ?? "@email@";
?>

<div class="modal" id="modal">

    <div class="modal__content">

        <div class="modal__header">
            <h1 class="modal__title"><?= $title ?></h1>
            <span class="modal__close" onclick="modalClose()">&#10005;</span>
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
                    placeholder="Enter username"
                    autocomplete="username">

                <label class="form__label" for="email">Email</label>
                <input class="form__input"
                    type="email"
                    id="email"
                    name="email"
                    value="<?= $email ?>"
                    placeholder="name@example.com"
                    autocomplete="email">

                <div class="form__group form__group--horizontal">
                    <label class="form__label" for="changePassword">Change passsword</label>
                    <input class="form__input"
                        type="checkbox"
                        id="changePassword"
                        name="changePassword"
                        onchange="togglePassword(event)"
                        value=true>
                </div>

                <fieldset id="password-changer" class="form__group" hidden>

                    <label class="form__label" for="password">Password</label>
                    <input class="form__input"
                        type="password"
                        id="password"
                        name="password"
                        placeholder="**********"
                        autocomplete="new-password">

                    <label class="form__label" for="confirmPassword">Confirm password</label>
                    <input class="form__input"
                        type="password"
                        id="confirmPassword"
                        name="confirmPassword"
                        placeholder="**********"
                        autocomplete="new-password">

                </fieldset>
            </form>
        </div>

        <div class="modal__footer">
            <div class="modal__button-group">
                <button type="submit" class="modal__button modal__button--green" onclick="modalSubmit()"><?= $submit ?></button>
                <button type="button" class="modal__button" onclick="modalClose()">Cancel</button>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        <?php include_once(FCPATH . 'js/modal.js') ?>

        var passwordGroup = document.getElementById('password-changer');

        var togglePassword = (event) => {
            const checkbox = event.target;
            if (checkbox.checked) {
                passwordGroup.hidden = false;
                passwordGroup.disabled = false;
            } else {
                passwordGroup.hidden = true;
                passwordGroup.disabled = true;
            }
        };

        if (document.querySelector('#modal input[name="id"]').value === "") {
            changePassword.click();
            changePassword.parentElement.remove();
        }
    </script>
</div>
