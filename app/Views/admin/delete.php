<?php
    /**
     * MODAL: confirmation for delete.
     *
     * @param string $itemType type of item to be deleted (material/...)
     * @param string $idName name of id parameter (required if name !== 'id')
     */
?>
<div class="modal" id="delete-window">

    <div class="modal__content">
        <div class="modal__header">
            <h1 id="delete-title">Confirm deletion</h1>
            <span class="close" id="delete-close">&#10005</span>
        </div>

        <div class="modal__body">
            <p id="delete-message">
                Warning: This action is irreversible!<br>
                Are you sure you want to delete <?= $itemType ?? 'item' ?> with id: <strong>[]</strong>?
            </p>
        </div>

        <div class="modal__footer">
            <div style="float:right">
                <button type="submit" class="modal__button modal__button--submit" onclick="deleteSubmit()">Delete</button>
                <button type="button" class="modal__button modal__button--close" onclick="deleteClose.click()">Cancel</button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        const deleteModal = document.getElementById("delete-window");

        const titleElement = deleteModal.querySelector('#delete-title');
        const titleOriginal = titleElement.innerHTML;

        const messageElement = deleteModal.querySelector('#delete-message');
        const messageOriginal = deleteMessage.innerHTML;

        document.getElementById("delete-close").onclick = function()
        {
            deleteModal.style.display = "none";
        }

        window.addEventListener("click", function(event)
        {
            if (event.target == deleteModal) {
                deleteModal.style.display = "none";
            }
        });

        deleteModal.addEventListener("submit", function(event) {
            event.preventDefault();
            
            const response = await fetch('
                <?= $action ?>'.replace(/[0-9]+$)/, deleteModal.getAttribute('data-value')),
                { method: 'DELETE' }
            );
    
            if (!response.ok) {
                messageElement.innerHTML = "Error: " +
                    "item with id: <strong>" +
                    deleteModal.getAttribute('data-value') +
                    "</strong> could not be deleted." +
                    "<br>Do you want to try again?";
            } else {
                document.getElementById("delete-close")?.remove();
                deleteModal.style.display = "none";
            }
        });

        const deleteOpen = function(id)
        {
            messageElement.innerHTML = messageOriginal.replace('[]', `[${id}]`);
            deleteModal.setAttribute('data-value', id);
            deleteModal.style.display = "block";
        }
    </script>
</div>
