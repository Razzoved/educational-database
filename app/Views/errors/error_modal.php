<div class="modal" tabindex="-1" role="dialog" id="error-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title"><?= $title ?></h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('error-modal').remove()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p><?= $message ?><p>
            </div>

        </div>
    </div>
</div>
