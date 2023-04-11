<?php
    /**
     * MODAL: Property editor.
     *
     * @param string $title Title of modal
     * @param string $submit Custom text for submit button
     *
     * @param array  $targets All possible assignment targets (array of id -> name)
     */
    
    $title = $title ?? "Create property";
    $submit = $submit ?? "Create";
    ?>

<div class="modal" id="property-window">
    <div class="modal__content">
        
        <div class="modal__header">
            <h1 class="modal__title"><?= $title ?></h1>
            <span class="modal__close" onclick="modalClose('property-window')">&#10005</span>
        </div>
        
        <div class="modal__body">
            <form class="form" method="post" action="<?= base_url('admin/tags')?>">
                <input type="hidden" id="id" name="id" value="<?= set_value('id') ?>">
                
                <fieldset class="form__group">
                    <label for="tag" class="form__label">Tag</label>
                    <input class="form__input"
                        type="text"
                        id="tag"
                        name="tag"
                        placeholder="Enter tag"
                        value="<?= set_value('tag') ?>"
                        required>
                    <label for="value" class="form__label">Value</label>
                    <input class="form__input"
                        type="text"
                        id="value"
                        name="value"
                        placeholder="Enter value"
                        value="<?= set_value('value') ?>"
                        required>
                </fieldset>
                    
                <label for="description" class="form__label">Description</label>
                <textarea class="form__input"
                    id="description"
                    name="description"
                    rows="2"
                    placeholder="Enter description...">
                </textarea>

                <fieldset class="slider">
                    <label for="priority" class="form__label">Priority</label>
                    <input class="slider__input"
                        type="range"
                        min="-25"
                        max="100"
                        value="0"
                        id="priority"
                        name="priority"
                        oninput="this.parentElement.querySelector('.slider__value').value=this.value">
                    <p class="slider__value">0</p>
                </fieldset>

            </form>
        </div>

        <div class="modal__footer">
            <div style="modal__button-group">
                <button type="modal__button modal__button--submit" onclick="propertySubmit()"><?= $submit ?? 'Submit' ?></button>
                <button type="modal__button modal__button--cancel" onclick="modalClose('property-window')">Cancel</button>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        <?= include_once(base_url('js/modal.js')) ?>

        let propertyEdit = false;
        let propertyModal = document.getElementById("property-window");

        // When the user clicks anywhere outside of the modal, close it
        window.addEventListener("click", function(event) {
            if (event.target == userModal) {
                userModal.style.display = "none";
            }
        });

        function propertyOpen(id = undefined)
        {
            propertyEdit = false;

            if (id !== undefined) {
                $.ajax({
                    type: 'GET',
                    url: '<?= base_url('admin/tags/edit') ?>',
                    data: { id },
                    dataType: 'json',
                    success: function(result) {
                        result = (result.id === undefined)
                            ? JSON.parse(result)
                            : result;
                        propertyEdit = true;
                        modalOpen('property-window', [
                            'id'          => result.id,
                            'tag'         => result.tag,
                            'value'       => result.value,
                            'priority'    => result.priority,
                            'description' => result.description
                        ]);
                    },
                    error: (jqHXR) => showError(jqHXR)
                });
            } else {
                modalOpen('user-window', []);
            }
        }

        function propertySubmit()
        {
            modalSubmit(
                'property-window',
                (result) => {
                    result = (result.id === undefined)
                        ? JSON.parse(result)
                        : result;
                    if (propertyEdit) {
                        updateData(result);
                    } else {
                        appendData(result);
                    }
                },
                propertyEdit ? 'edit' : 'new'
            );
        }
    <script>
</div>
