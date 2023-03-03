<?php
    /**
     * MODAL: confirmation for delete.
     *
     * @param string $itemType type of item to be deleted (material/...)
     * @param string $idName name of id parameter (required if name !== 'id')
     */
?>
<div class="modal" id="delete-window">

    <div class="modal-content">
        <div class="modal-header">
            <h1>Confirm deletion</h1>
            <span class="close" id="delete-close">&#10005</span>
        </div>

        <div class="modal-body">
            <p>Warning: This action is irreversible!</p>
            <p id="warnMsg">Are you sure you want to delete <?= $itemType ?? 'item' ?> with id: <strong>[]</strong>?</p>
        </div>

        <div class="modal-footer">
            <div style="float:right">
                <button type="button" class="btn btn-submit" onclick="deleteSubmit()">Yes</button>
                <button type="button" class="btn btn-danger" onclick="deleteClose.click()">No</button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var deleteModal = document.getElementById("delete-window");
        var deleteClose = document.getElementById("delete-close");

        // When the user clicks on <span> (x), close the modal
        deleteClose.onclick = function() {
            deleteModal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.addEventListener("click", function(event) {
            if (event.target == deleteModal) {
                deleteModal.style.display = "none";
            }
        });

        /** Function for buttons that need delete box */
        function deleteOpen(id)
        {
            let warn = deleteModal.querySelector('#warnMsg');
            warn.innerHTML = warn.innerHTML.replace(/\[.*\]/, `[${id}]`);
            deleteModal.setAttribute('data-value', id);
            deleteModal.style.display = "block";
        }

        function deleteSubmit()
        {
            $.ajax({
                type: 'POST',
                url: '<?= $action ?>',
                data: {<?= $idName ?? 'id' ?>: deleteModal.getAttribute('data-value')},
                dataType: 'json',
                success: function(data) {
                    deleteId(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Cannot delete: " + errorThrown);
                }
            })
            deleteModal.style.display = "none";
        }
    </script>
</div>
