<?php
    /**
     * MODAL: User editor.
     *
     * @param string $title Title of modal
     * @param string $submit Custom text for submit button
     */
?>
<div class="modal" id="user-window">

    <div class="modal-content">
        <div class="modal-header">
            <h1><?= $title ?? 'New user' ?></h1>
            <span class="close" id="user-close" onclick="userClose()">&#10005</span>
        </div>

        <div class="modal-body">
            <?php $extra = ['class' => 'form-control'] ?>
            <?php helper('form') ?>

            <?= form_open(base_url('admin/users'), ['id' => 'user-form']) ?>
            <div class="form-floating">
                <?= form_input(['id' => 'fName', 'name' => 'name', 'placeholder' => 'username'], $name ?? '', $extra) ?>
                <?= form_label('Username', 'fName') ?>
            </div>
            <div class="form-floating">
                <?= form_input(['id' => 'fEmail', 'name' => 'email', 'placeholder' => 'name@example.com'], $email ?? '', $extra, 'email') ?>
                <?= form_label('Email address', 'fEmail') ?>
            </div>

            <small id="pass-notice" hidden='true'>Password will not change if left empty:</small>

            <div class="form-floating">
                <?= form_password(['id' => 'fPassword', 'name' => 'password', 'placeholder' => 'Password'], '', $extra) ?>
                <?= form_label('Password', 'fPassword') ?>
            </div>
            <div class="form-floating">
                <?= form_password(['id' => 'fConfirmPassword', 'name' => 'confirmPassword', 'placeholder' => 'Password'], '', $extra) ?>
                <?= form_label('Confirm password', 'fConfirmPassword') ?>
            </div>
            <?= form_close() ?>
        </div>

        <div class="modal-footer">
            <div style="display: flex; justify-content: space-between;">
                <button type="button" class="btn btn-primary me-2" style="width: 5rem" onclick="userSubmit()"><?= $submit ?? 'Submit' ?></button>
                <button type="button" class="btn btn-danger" style="width: 5rem" onclick="userClose()">Cancel</button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        let userModal = document.getElementById("user-window");
        let userName = userModal.querySelector("#fName");
        let userEmail = userModal.querySelector("#fEmail");
        let userPassword = userModal.querySelector("#fPassword");
        let userConfirmPassword = userModal.querySelector("#fConfirmPassword");
        let passwordNote = document.getElementById('pass-notice');
        let isEdit = false;

        // When the user clicks anywhere outside of the modal, close it
        window.addEventListener("click", function(event) {
            if (event.target == userModal) {
                userModal.style.display = "none";
            }
        });

        async function userClose()
        {
            userModal.style.display = 'none';
        }


        function userOpen(id = undefined)
        {
            userReset();

            if (id !== undefined) {
                $.ajax({
                    type: 'GET',
                    url: '<?= base_url('admin/users/edit') ?>',
                    data: {email: id},
                    dataType: 'json',
                    success: function(user) {
                        user = parseUser(user);
                        isEdit = true;
                        userName.value = user.name;
                        userEmail.value = user.email;
                        userEmail.readOnly = true;
                        userModal.style.display = "block";
                        passwordNote.hidden = false;
                    },
                    error: (jqHXR) => showError(jqHXR)
                });
            } else {
                userModal.style.display = "block";
            }
        }

        function userReset()
        {
            isEdit = false;
            // reset errors
            document.querySelector('#user-error')?.remove();
            // reset form values
            userName.value = '';
            userEmail.value = '';
            userEmail.readOnly = undefined;
            userPassword.value = '';
            userConfirmPassword.value = '';
            passwordNote.hidden = true;
        }

        function userSubmit()
        {
            document.querySelector('#user-error')?.remove();
            let form = $('#user-form');
            $.ajax({
                type: 'POST',
                url: form.attr('action') + (isEdit ? '/edit' : '/new'),
                dataType: 'json',
                data: form.serialize(),
                success: function(user) {
                    user = parseUser(user);
                    if (isEdit) {
                        updateData(user);
                    } else {
                        appendData(user);
                    }
                    userClose();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    let error = document.createElement('div');
                    error.id = 'user-error';
                    error.innerHTML = jqXHR.responseText !== '' ? jqXHR.responseText : errorThrown;
                    document.getElementById('user-form').prepend(error);
                }
            })
        }

        function parseUser(user)
        {
            return user.email === undefined ? JSON.parse(user) : user;
        }
    </script>
</div>
