<?php
    /**
     * MODAL: Resource assigner.
     *
     * @param string $title Title of modal
     * @param string $submit Custom text for submit button
     *
     * @param array  $targets All possible assignment targets (array of id -> name)
     */
    $title = $title ?? 'Assign resource';
    $submit = $submit ?? 'Assign';
?>

<div class="modal" id="resource-window">

    <div class="modal__content">

        <div class="modal__header">
            <h1 class="modal__title"><?= $title ?></h1>
            <span class="modal__close" onclick="modalClose('resource-window')">&#10005</span>
        </div>

        <div class="modal__body">
            <form class="form" method="post" action="<?= url_to('Admin\Resource::assign') ?>">
                <input type="hidden" id="tmp_path" name="tmp_path" value="">
                <label class="form__label" for="target">Assign to</label>
                <input class="form__input"
                    id="target"
                    name="target"
                    list="target-options"
                    placeholder="No material selected"
                    onblur="verifyTarget()">
                <datalist id="target-options">
                    <?php foreach ($targets as $id => $title) : ?>
                        <option value='<?= esc($title) ?>'><?= $id ?></option>
                    <?php endforeach; ?>
                </datalist>
            </form>
        </div>

        <div class="modal__footer">
            <div class="modal__button-group">
                <button type="submit" class="modal__button modal__button--submit" onclick="modalSubmit()"><?= $submit ?></button>
                <button type="button" class="modal__button modal__button--cancel" onclick="modalClose('resource-window')">Cancel</button>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        <?= include_once(FCPATH . 'js/modal.js') ?>

        const target = document.getElementById('target');
        const options = document.getElementById('target-options');

        const verifyTarget = function()
        {
            const option = options.querySelector(
                `option[value="${target.value.replaceAll('"', '\\"')}"]`
            );

            if (target.value !== "" && option === null) {
                target.classList.add('form__input--invalid');
                target.value = "";
            } else {
                target.classList.remove('form__input--invalid');
            }
        }
    </script>
</div>
