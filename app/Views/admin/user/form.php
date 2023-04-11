<?php
    /**
     * MODAL: User editor.
     *
     * @param string $title Title of modal
     * @param string $submit Custom text for submit button
     */

    $title = $title ?? 'New user';
    $submit = $submit ?? 'Create';
?>

<div class="modal" id="user-window">

    <div class="modal__content">

        <div class="modal__header">
            <h1 class="modal__title"><?= $title ?></h1>
            <span class="modal__close" onclick="modalClose('user-window')">&#10005</span>
        </div>

        <div class="modal__body">
            <form class="form" method="post" action="<?= base_url('admin/users')?>">
                <input type="hidden" id="id" name="id">

                <label class="form__label" for="name">Username</label>
                <input class="form__input"
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter username">
                
                <label class="form__label" for="email">Email</label>
                <input class="form__input"
                    type="email"
                    id="email"
                    name="email"
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
        <?= include_once(base_url('js/modal.js')) ?>

        let userEdit = false;
        let userModal = document.getElementById("user-window");
        let passwordNote = document.getElementById('pass-notice');

        // When the user clicks anywhere outside of the modal, close it
        window.addEventListener("click", function(event) {
            if (event.target == userModal) {
                userModal.style.display = "none";
            }
        });

        function userOpen(id = undefined)
        {
            userEdit = false;
            passwordNote.hidden = false;

            if (id !== undefined) {
                $.ajax({
                    type: 'GET',
                    url: '<?= base_url('admin/users/edit') ?>',
                    data: { id },
                    dataType: 'json',
                    success: function(result) {
                        result = (result.id === undefined)
                            ? JSON.parse(result)
                            : result;
                        userEdit = true;
                        passwordNote.hidden = false;
                        modalOpen('user-window', [
                            'id'    => result.id,
                            'name'  => result.name,
                            'email' => result.email,
                        ]);
                    },
                    error: (jqHXR) => showError(jqHXR)
                });
            } else {
                modalOpen('user-window', []);
            }
        }

        function userSubmit()
        {
            modalSubmit(
                'user-window',
                (result) => {
                    result = (result.id === undefined)
                        ? JSON.parse(result)
                        : result;
                    if (userEdit) {
                        updateData(result);
                    } else {
                        appendData(result);
                    }
                },
                userEdit ? 'edit' : 'new'
            );
        }
    </script>
</div>
