<?php

/** Intended primarily for use by controllers.
 *
 * @param string $title
 * @param string $message
 */
$title = $title ?? "@title@";
$message = $message ?? "@message@";
?>

<div class="modal modal--visible" tabindex="-1" id="error-modal">

    <div class="modal__content">

        <div class="modal__header">
            <h1 class="modal__title" id="error-title"><?= $title ?></h1>
            <span class="modal__close" onclick="document.getElementById('error-modal').remove()">&#10005;</span>
        </div>

        <div class="modal__body">
            <p id="error-message" style="color: red">Error: <?= $message ?>
            <p>
        </div>

        <div class="modal__footer">
            <div class="modal__button-group">
                <button type="button" class="modal__button" onclick="document.getElementById('error-modal').remove()">Close</button>
            </div>
        </div>

    </div>

</div>