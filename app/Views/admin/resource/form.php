<?php
    /**
     * MODAL: Resource assigner.
     *
     * @param string $title   Title of modal
     * @param string $submit  Custom text for submit button
     * @param array  $targets All possible materials.
     */
    $title = 'Add to material';
    $submit = 'Assign';

    $tmpPath = $tmpPath ?? '@tmp_path@';
?>

<div class="modal" id="modal">

    <div class="modal__content">

        <div class="modal__header">
            <h1 class="modal__title"><?= $title ?></h1>
            <span class="modal__close" onclick="modalClose()">&#10005;</span>
        </div>

        <div class="modal__body">
            <form class="form" method="post" action="<?= url_to('Admin\Resource::assign') ?>">
                <input type="hidden" id="tmp_path" name="tmp_path" value="<?= $tmpPath ?>" required>
                <label class="form__label" for="target">Assign to<i id="target-display"></i></label>
                <input class="form__input"
                    id="target"
                    name="target"
                    list="target-options"
                    placeholder="No material selected"
                    required>
                <datalist id="target-options">
                    <?php foreach ($targets as $id => $title) : ?>
                        <option value='<?= $id ?>'><?= esc($title) ?></option>
                    <?php endforeach; ?>
                </datalist>
            </form>
        </div>

        <div class="modal__footer">
            <div class="modal__button-group">
                <button type="submit" class="modal__button modal__button--submit" onclick="verifyTarget() && modalSubmit()"><?= $submit ?></button>
                <button type="button" class="modal__button modal__button--cancel" onclick="modalClose()">Cancel</button>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        <?= include_once(FCPATH . 'js/modal.js') ?>

        const target = document.getElementById('target');
        const targetDisplay = document.getElementById('target-display');
        const targetOptions = document.getElementById('target-options');

        const verifyTarget = function()
        {
            const option = targetOptions.querySelector('option:selected');
            if (option === null) {
                target.value = '';
                targetDisplay.innerHTML = '';
                target.focus();
            } else {
                targetDisplay.innerHTML = `: ${option.innerHTML}`;
            }
            return option !== null;
        }
    </script>
</div>
