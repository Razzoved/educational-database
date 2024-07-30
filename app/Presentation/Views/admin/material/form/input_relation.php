<?php

/**
 * Partial view that generates relation input form.
 * It requires dynamics javascript files to be loaded.
 * Bootstrap should be loaded too.
 *
 * Expects:
 * @param available all available materials in form of (id => title) pairs
 * @param relations relations to other materials that already exist
 */
?>

<div class="form__group">

    <!-- relation uploader -->
    <div class="form__group form__group--horizontal-flex">
        <input type="text" id="relation-uploader" list="relation-options" class="form__input" placeholder="No material selected" title="Links materials together (relation will be visible in both)!" onblur="uniqueRelation()">
        <button class="form__button" type="button" onclick="newRelation()">Add</button>
    </div>

    <datalist id="relation-options">
    </datalist>

    <div class="form__group" id="relation-group">
        <?php foreach ($relations as $relation) {
            echo view('admin/material/form/item_relation', [
                'id'    => $relation->id,
                'title' => $relation->title,
                'url'   => url_to('Material::get', $relation->id),
            ]);
        } ?>
    </div>
</div>

<script>
    <?php include_once(FCPATH . 'js/fetch.js'); ?>

    const relationUploader = document.getElementById('relation-uploader');
    const relationGroup = document.getElementById('relation-group');

    const uniqueRelation = () => {
        // check for duplicates
        const relations = relationGroup.querySelectorAll('input[name^="relation"]');
        for (var key in relations) {
            if (relationUploader.value === relations[key].value) {
                return relationUploader.setInvalid(true);
            }
        }
        return relationUploader.setInvalid(false) && relationUploader.value !== "";
    }

    const newRelation = () => {
        const option = relationUploader.verifyOption();

        // check for self
        const id = document.querySelector('input[name="id"]');

        if (!uniqueRelation() || !option || (id && option.getAttribute('data-id') === id.value)) {
            relationUploader.focus();
            return relationUploader.setInvalid(true);
        }

        // create a new relatiom
        const template = `<?= view('admin/material/form/item_relation') ?>`.fill({
            id: option.getAttribute('data-id'),
            title: option.value,
            url: '<?= url_to('Material::get', 0) ?>'.replace(/0$/, option.getAttribute('data-id')),
        });
        relationUploader.value = "";
        relationGroup.insertAdjacentHTML('beforeend', template);
    }

    document.addEventListener('DOMContentLoaded', () => {
        secureFetch('<?= url_to('Admin\Material::getAvailable') ?>')
            .then(response => response.json())
            .then(response => response.map(r => {
                const option = document.createElement('option');
                option.value = r.title;
                option.setAttribute('data-id', r.id);
                return option;
            }))
            .then(response => relationUploader.list.replaceChildren(...response))
            .catch(error => console.log('No relations fetched'));
    });
</script>