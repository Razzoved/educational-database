<?php
    /**
     * MODAL: Property editor.
     *
     * @param string $title Title of modal
     * @param string $submit Custom text for submit button
     *
     * @param array  $targets All possible assignment targets (array of id -> name)
     */

    $title = isset($id) ? 'Update property' : "Create property";
    $submit = isset($id) ? 'Update' : "Create";

    $id = $id ?? "";
    $tag = $tag ?? "";
    $value = $value ?? "";
    $description = $description ?? "";
    $priority = $priority ?? 0;
?>

<div class="modal" id="property-window">
    <div class="modal__content">

        <div class="modal__header">
            <h1 class="modal__title"><?= $title ?></h1>
            <span class="modal__close" onclick="modalClose()">&#10005;</span>
        </div>

        <div class="modal__body">
            <form class="form" method="post" action="<?= url_to('Admin\Property::index')?>">
                <input type="hidden" id="id" name="id" value="<? $id ?>">

                <fieldset class="form__group">
                    <label for="tag" class="form__label">Category</label>
                    <input class="form__input"
                        type="text"
                        id="tag"
                        name="tag"
                        placeholder="Enter tag"
                        value="<?= $tag ?>"
                        required>
                    <label for="value" class="form__label">Value</label>
                    <input class="form__input"
                        type="text"
                        id="value"
                        name="value"
                        placeholder="Enter value"
                        value="<?= $value ?>"
                        required>
                </fieldset>

                <label for="description" class="form__label">Description</label>
                <textarea class="form__input"
                    id="description"
                    name="description"
                    rows="2"
                    placeholder="Enter description..."
                    value="<?= $description ?>">
                </textarea>

                <fieldset class="slider">
                    <label for="priority" class="form__label">Priority</label>
                    <input class="slider__input"
                        type="range"
                        min="-25"
                        max="100"
                        value="<?= $priority ?>"
                        id="priority"
                        name="priority"
                        onchange="updateSlider(this)">
                    <p class="slider__value">0</p>
                </fieldset>

            </form>
        </div>

        <div class="modal__footer">
            <div class="modal__button-group">
                <button type="submit" class="modal__button modal__button--submit" onclick="modalSubmit()"><?= $submit ?></button>
                <button type="button" class="modal__button modal__button--cancel" onclick="modalClose('property-window')">Cancel</button>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        <?= include_once(FCPATH . 'js/modal.js') ?>

        const updateSlide = function(sliderInput)
        {
            const sliderValue = sliderInput.closest('.slider').querySelector('.slider__value');
            sliderValue.innerHTML = sliderInput.value;
        }
    </script>
</div>
