<?php
    /** Intended primarily for use by controllers.
     *
     * @param string $title
     * @param string $message 
     */
?>

<div class="modal" tabindex="-1" role="dialog" id="error-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h1 id="error-title" class="modal-title"><?= $title ?></h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('error-modal').remove()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p id="error-message"><?= $message ?><p>
            </div>

        </div>
    </div>
</div>
