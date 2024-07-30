<?php

/**
 * MODAL: Property editor.
 *
 * @param string $title Title of modal
 * @param string $submit Custom text for submit button
 */
$title = $title ?? '@title@';
$submit = $submit ?? '@submit@';

$id = $id ?? "@id@";
$tag = $tag ?? "@tag@";
$category = $category ?? "@category@";
$value = $value ?? "@value@";
$description = $description ?? "@description@";
$priority = $priority ?? "@priority@";
?>

<div class="modal" id="modal">
    <div class="modal__content">

        <div class="modal__header">
            <h1 class="modal__title"><?= $title ?></h1>
            <span class="modal__close" onclick="modalClose()">&#10005;</span>
        </div>

        <div class="modal__body">
            <form class="form" method="post" action="<?= url_to('Admin\Property::save') ?>" autocomplete="off">
                <input type="hidden" id="id" name="id" value="<?= $id ?>" required>

                <fieldset class="form__group">
                    <label for="category" class="form__label">Category</label>
                    <input class="form__input" id="category" name="category" list="category-options" placeholder="Enter tag" value="<?= $category ?>" oninput="updateTag()" required>
                    <datalist id="category-options">
                    </datalist>
                    <input type="hidden" id="tag" name="tag" value="<?= $tag ?>">

                    <label for="value" class="form__label">Value</label>
                    <input class="form__input" type="text" id="value" name="value" placeholder="Enter value" value="<?= $value ?>" required>
                </fieldset>

                <fieldset class="form__group">
                    <label for="description" class="form__label">Description</label>
                    <textarea class="form__input" id="description" name="description" rows="2" placeholder="Enter description..."><?= $description ?></textarea>

                    <div class="form__group form__group--horizontal slider">
                        <label for="priority" class="form__label">Priority</label>
                        <input class="slider__input" type="range" min="-25" max="100" value="<?= $priority ?>" id="priority" name="priority" oninput="updateSliderValue(this)">
                        <input class="form__input slider__value" type="number" title="Priority value" value="<?= $priority ?>" oninput="updateSliderInput(this)" required>
                    </div>
                </fieldset>

            </form>
        </div>

        <div class="modal__footer">
            <div class="modal__button-group">
                <button type="submit" class="modal__button modal__button--green" onclick="validate() && <?= $onSubmit ?? 'modalSubmit()' ?>"><?= $submit ?></button>
                <button type="button" class="modal__button" onclick="modalClose()">Cancel</button>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        <?php include_once(FCPATH . 'js/fetch.js'); ?>
        <?php include_once(FCPATH . 'js/modal.js') ?>

        var propertyModal = document.getElementById('modal');

        var tag = document.getElementById('tag');
        var category = document.getElementById('category');
        var categoryOptions = document.getElementById('category-options');

        var validate = () => {
            const message = 'Invalid category';
            const result = category.value == '' ? true : category.verifyOption();
            if (result) {
                modalClearError(propertyModal, message);
            } else {
                modalSetError(propertyModal, message);
            }
            return result;
        }

        var updateTag = () => {
            const option = validate();
            if (option) {
                tag.value = option === true ? 0 : option.getAttribute('data-tag');
            }
        }

        var updateSliderValue = (slider) => {
            const sliderValue = slider.closest('.slider').querySelector('.slider__value');
            sliderValue.value = slider.value;
        }

        var updateSliderInput = (slider) => {
            const sliderInput = slider.closest('.slider').querySelector('.slider__input');
            if (parseInt(slider.value) > parseInt(sliderInput.max)) {
                slider.value = sliderInput.max;
            }
            if (parseInt(slider.value) < parseInt(sliderInput.min)) {
                slider.value = sliderInput.min;
            }
            sliderInput.value = slider.value;
        }

        secureFetch('<?= url_to('Admin\Property::getAvailable') ?>')
            .then(response => response.json())
            .then(response => response.map(r => {
                const option = document.createElement('option');
                option.value = r.value;
                option.setAttribute('data-tag', r.id);
                return option;
            }))
            .then(response => categoryOptions.replaceChildren(...response))
            .then(() => updateTag())
            .catch(error => console.log('No categories fetched'));
    </script>
</div>