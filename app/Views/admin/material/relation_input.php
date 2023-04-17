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
    <div class="form__group form__group--horizontal">
        <input type="text"
            id="relation-uploader"
            list="relation-options"
            class="form__input"
            placeholder="No material selected"
            onblur="verifyRelation()">
        <button class="form__input" type="button" onclick="newRelation()">Add</button>
    </div>

    <datalist id="relation-options">
        <?php foreach ($available as $id => $title) : ?>
            <option value='<?= esc($title) ?>' data-value='<?= $id ?>'>
        <?php endforeach; ?>
    </datalist>

    <!-- hidden template for js copying -->
    <?= view('admin/material/relation_template', ['id' => null, 'value' => null, 'hidden' => true, 'readonly' => true]) ?>

    <div class="form__group" id="relation-group">
    <?php
        foreach ($relations as $id => $title) {
            echo view('admin/material/relation_template', ['id' => $id, 'value' => $title, 'hidden' => false, 'readonly' => true]);
        }
    ?>
    </div>
</div>

<script>
    function newRelation()
    {
        let uploader = document.getElementById('relation-uploader');
        let container = document.getElementById('relation-group');

        if (!verifyRelation() || !verifyRelationDuplicates()) {
            uploader.classList.add('border-danger');
            return;
        }

        let option = document.getElementById('relation-options').querySelector(`option[value="${escapeQuotes(uploader.value)}"]`);
        let relation = createRelation(
            `relation-${parseInt(container.lastElementChild?.id.replace(/^\D+/g, '') ?? '0') + 1}`,
            uploader.value,
            option?.dataset.value
        );
        container.appendChild(relation);
        uploader.value = "";
    }

    function createRelation(id, value, idValue) {
        let newDiv = document.getElementById("relation-template").cloneNode(true);
        let input = newDiv.querySelector('input');
        let a = newDiv.querySelector('a');
        let button = newDiv.querySelector('button');

        if (input === undefined || button === undefined || a === undefined) console.warn("invalid relation template: undefined")
        if (value === undefined) console.warn("invalid value");
        if (idValue === undefined) console.warn("invalid idValue");

        input.disabled = null;
        input.required = true;
        input.setAttribute('value', value);
        input.setAttribute('name', input.name.replace(/[0-9]/, idValue));

        button.onclick = () => removeById(id);

        a.href = '<?= url_to('Material::get', $idValue) ?>'
        a.innerHTML = value;

        newDiv.id = id;
        newDiv.hidden = false;

        return newDiv;
    }

    function verifyRelation()
    {
        let uploader = document.getElementById('relation-uploader');
        let option = document.getElementById('relation-options').querySelector(`option[value="${escapeQuotes(uploader.value)}"]`);
        if (option === null && uploader.value !== "") {
            uploader.classList.add('border-danger');
            return false;
        }
        uploader.classList.remove('border-danger');
        return uploader.value !== "";
    }

    function verifyRelationDuplicates()
    {
        let uploader = document.getElementById('relation-uploader');
        let duplicate = document.getElementById('relation-group').querySelector('input[value="' + escapeQuotes(uploader.value) + '"]');
        if (duplicate !== null) uploader.value = '';
        return duplicate === null;
    }

    function escapeQuotes(string)
    {
        return string.replaceAll('"', '\\"');
    }
</script>
