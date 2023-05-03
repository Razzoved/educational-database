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

<div class="modal" id="property-window">
    <div class="modal__content">

        <div class="modal__header">
            <h1 class="modal__title"><?= $title ?></h1>
            <span class="modal__close" onclick="modalClose()">&#10005;</span>
        </div>

        <div class="modal__body">
            <form class="form" method="post" action="<?= url_to('Admin\Property::save')?>">
                <input type="hidden" id="id" name="id" value="<? $id ?>" required>

                <fieldset class="form__group">
                    <label for="tag" class="form__label">Category</label>
                    <input class="form__input"
                        id="category"
                        name="category"
                        list="category-options"
                        placeholder="Enter tag"
                        value="<?= $category ?>"
                        onchange="updateTag(this.value)"
                        required>
                    <datalist id="category-options">
                    </datalist>
                    <input type="hidden" id="tag" name="tag" value="<? $tag ?>">

                    <label for="value" class="form__label">Value</label>
                    <input class="form__input"
                        type="text"
                        id="value"
                        name="value"
                        placeholder="Enter value"
                        value="<?= $value ?>"
                        required>
                </fieldset>

                <fieldset class="form__group">
                    <label for="description" class="form__label">Description</label>
                    <textarea class="form__input"
                        id="description"
                        name="description"
                        rows="2"
                        placeholder="Enter description..."
                        value="<?= $description ?>">
                    </textarea>

                    <div class="slider">
                        <label for="priority" class="form__label">Priority</label>
                        <input class="slider__input"
                            type="range"
                            min="-25"
                            max="100"
                            value="<?= $priority ?>"
                            id="priority"
                            name="priority"
                            onchange="updateSlider(this.parentElement)">
                        <p class="slider__value">0</p>
                    </div>
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
        const tag = document.getElementById('tag');
        const category = document.getElementById('category');
        const categoryOptions = document.getElementById('category-options');

        const updateTag = (value) => {
            const option = categoryOptions.querySelector('option:selected');
            if (!option === null) {
                tag.value = option.getAttribute('data-tag');
            }
        }

        const updateSlider = (slider) => {
            const sliderInput = slider.querySelector('.slider__input');
            const sliderValue = slider.querySelector('.slider__value');
            sliderValue.innerHTML = sliderInput.value;
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetch(<?= url_to('Admin/Property::getAll') ?>)
                .then(response => response.json())
                .then(response => response.map(r => {
                    const option = document.createElement('option');
                    option.value = r.value;
                    option.setAttribute('data-tag', r.id);
                    return option;
                }))
                .then(response => categoryOptions.replaceChildren(...response))
                .catch(error => console.log('Error fetching categories'));
        });
    </script>
</div>
