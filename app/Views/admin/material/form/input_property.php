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
    <button type="button" class="form__button form__button--large" onclick="propertyLockUnlock()">Change</button>
    <button type="button" class="form__button form__button--large" onclick="propertyCreate()">Create</button>
</div>

<div class="properties properties--unlocked" id="property0">
</div>

<script>
    <?php include_once(FCPATH . 'js/fetch.js'); ?>
    <?php include_once(FCPATH . 'js/modal.js') ?>

    const propertyRoot = document.getElementById('property0');

    const propertyBuild = (tree => {
        const template = `<?= view('admin/material/form/item_property') ?>`;

        const getParent = (property) => {
            return property.tag == 0
                ? propertyRoot
                : document.getElementById(`property${property.tag}`).querySelector(':scope > .property__children');
        }

        const build = (property) => {
            const parent = getParent(property);
            const element = template.html(property);
            parent.insertAdjacentElement('beforeend', element);

            const input = element.querySelector(':scope > input');
            input.disabled = true;

            if (property.children.length > 0) {
                property.children.forEach(p => build(p));
            }
        }

        if (tree.children.length > 0) {
            tree.children.forEach(c => build(c));
        }

        return (property) => {
            const parent = getParent(property);
            const element = template.html(property);
            parent.insertAdjacentElement('beforeend', template.html(property));
            element.classList.add('property--active');
        }
    })(<?= json_encode($available) ?>);

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

    const propertyLockUnlock = () => propertyRoot.classList.toggle('properties--unlocked');

    const propertyToggle = (element) => {
        if (propertyRoot.classList.contains('properties--unlocked')) {
            console.log('TEST: clicked');

            const input = element.querySelector(':scope > input');
            input.disabled = !input.disabled;
            element.classList.toggle('property--active');

            Array.from(element.querySelectorAll(':scope > .property__children > .property')).forEach(p => {
                console.log('clicked', p);
                p.click();
            })
        }
    }
</script>
