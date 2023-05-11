<?php
    /**
     * Partial view that generates property form selections.
     * It requires property_selector javascript file to be loaded.
     *
     * @param array $available  properties that are available at load time
     * @param array $properties properties already loaded into the material
     */
    helper('form');
?>
<div class="form__group form__group--horizontal-flex">
    <button id="property-toggle" type="button" class="form__button form__button--large" onclick="propertyLockUnlock()">Change</button>
    <button id="property-prev" type="button" class="form__button" onclick="propertyPrev(this)" hidden disabled>Previous</button>
    <button id="property-next" type="button" class="form__button" onclick="propertyNext(this)" hidden disabled>Next</button>
    <button type="button" class="form__button form__button--large" onclick="propertyCreate()">Create</button>
</div>

<div class="property" id="property0">
</div>

<script>
    <?php include_once(FCPATH . 'js/fetch.js'); ?>
    <?php include_once(FCPATH . 'js/modal.js') ?>
    <?php include_once(FCPATH . 'js/property.js') ?>

    const propertyRoot = document.getElementById('property0');

    /* PROPERTY APPENDING */

    const propertyBuild = (tree => {
        const template = `<?= view('admin/material/form/item_property') ?>`;

        const addToParent = (property) => {
            let parent = property.tag == 0
                ? propertyRoot
                : document.getElementById(`property${property.tag}`);

            if (parent !== propertyRoot) {
                parent.classList.add('property--non-empty');
                parent = parent.querySelector(':scope > .property__children');
            }
            const element = template.html(property);
            parent.insertAdjacentElement('beforeend', element);
            return element;
        }

        const build = (property) => {
            const element = addToParent(property);
            const input = element.querySelector(':scope > input');
            input.disabled = true;
            if (property.children.length > 0) {
                property.children.forEach(p => build(p));
            }
        }

        if (tree.children.length > 0) {
            tree.children.forEach(c => build(c));
            propertyRoot.firstElementChild.classList.add('property__item--current');
        }

        return (property) => {
            const element = addToParent(property);
            element.click();
        }
    })(<?= json_encode($available) ?>);

    /* PROPERTY CREATION SECTION */

    const formTemplate = `<?= json_encode(view(
        'admin/property/form',
        ['title' => null, 'submit' => null, 'onSubmit' => 'propertySubmit(this)']
    )) ?>`;

    const propertyCreate = () => {
        const template = formTemplate.fill({
            title: 'New tag',
            submit: 'Create',
            id: "",
            tag: "",
            value: "",
            category: "",
            description: "",
            priority: 0,
        });
        modalOpen(undefined, template);
    }

    const propertySubmit = async (btn) => {
        const modal = btn.closest('.modal');
        const form = modal.querySelector('form');
        if (modal) try {
            const response = await secureFetch(form.action, {
                method: form.method,
                body: new FormData(form),
            }).then(response => {
                if (!response.ok) {
                    throw new Error(response.statusText);
                }
                return response.json();
            });
            propertyBuild(response);
            modalClose(null);
        } catch (error) {
            modalSetError(modal, error.message);
        }
    }
</script>
