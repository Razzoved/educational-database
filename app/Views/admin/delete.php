<?php
    /**
     * MODAL: confirmation for delete.
     *
     * @param string $itemType type of item to be deleted (material/...)
     */
?>
<div class="modal" id="modal-delete">

    <div class="modal__content">
        <div class="modal__header">
            <h1 id="delete-title">Confirm deletion</h1>
            <span class="modal__close" id="delete-close" onclick="deleteClose()">&#10005</span>
        </div>

        <div class="modal__body">
            <form class="form" data-method="DELETE">
                <?= csrf_field() ?>
                <p id="delete-message">
                    Warning: This action is irreversible!<br>
                    Are you sure you want to delete <?= $itemType ?? 'item' ?> with id: <strong>[]</strong>?
                </p>
            </form>
        </div>

        <div class="modal__footer">
            <div class="modal__button-group">
                <button type="submit" class="modal__button modal__button--red" onclick="deleteSubmit()">Delete</button>
                <button type="button" class="modal__button" onclick="deleteClose()">Cancel</button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        <?php include_once(FCPATH . 'js/fetch.js'); ?>
        <?php include_once(FCPATH . 'js/modal.js'); ?>

        const deleteModal = document.getElementById("modal-delete");
        const deleteAction = "<?= $action ?>";
        const deleteSubmitBtn = deleteModal.querySelector("button[type='submit']");

        const messageElement = deleteModal.querySelector('#delete-message');
        const messageOriginal = messageElement.innerHTML;

        const deleteOpenAll = (action) => {
            if (action === undefined || action === null) {
                console.error("Action not defined");
                return;
            }
            messageElement.innerHTML = messageOriginal.replace('[]', `[ALL]`);
            deleteModal.querySelector("form").action = action;
            deleteModal.classList.add('modal--visible');
        }

        const deleteOpen = (id) => {
            if (id === undefined || id === null) {
                console.error("Invalid id", id);
                return;
            }
            messageElement.innerHTML = messageOriginal.replace('[]', `[${id}]`);
            deleteModal.querySelector("form").action = deleteAction.replace(/([0-9]+|@segment@)/, id);
            deleteModal.classList.add('modal--visible');
        }

        const deleteClose = () => {
            modalClose(null, 'modal-delete');
        }

        const deleteSubmit = () => {
            modalSubmit(true, 'modal-delete');
        }

        window.addEventListener("click", (event) => event.target === deleteModal && deleteClose());
    </script>
</form>
