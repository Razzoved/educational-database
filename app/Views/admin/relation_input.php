<?php
    /**
     * Partial view that generates relation input form.
     * It requires dynamics javascript files to be loaded.
     * Bootstrap should be loaded too.
     *
     * Expects:
     * @param materials relations to other materials that already exist
     */
?>

<div class="dynamic-input">

    <!-- relation uploader -->
    <div class="row g-0">
        <input id="relation-uploader"
            list="relation-options"
            class="form-control col edit-mr"
            placeholder="No material selected"
            onblur="verifyRelation()">
        <button class="btn btn-md btn-secondary col-2" type="button" onclick="newRelation()">Add</button>
    </div>

    <datalist id="relation-options">
        <?php foreach ($materials as $material) : ?>
            <option value=<?= $material->title ?> data-value=<?= $material->id ?>>
        <?php endforeach; ?>
    </datalist>

    <!-- hidden template for js copying -->
    <?= view('admin/relation_template', ['id' => null, 'value' => null, 'hidden' => true, 'readonly' => true]) ?>

    <div id="relation-group">
    <?php
        $index = 0;
        foreach ($links as $link) {
            echo view('admin/relation_template', ['id' => $index, 'value' => $link, 'hidden' => false, 'readonly' => true]);
            $index++;
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

        let option = document.getElementById('relation-options').querySelector('option[value="' + uploader.value + '"]');
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
        let input = newDiv.firstElementChild;
        let button = newDiv.lastElementChild;

        if (input === undefined || button === undefined) console.warn("invalid relation template: undefined")
        if (input === button) console.warn("invalid relation template: input=button")
        if (value === undefined) console.warn("invalid value");
        if (idValue === undefined) console.warn("invalid idValue");

        input.disabled = null;
        input.required = true;
        input.setAttribute('value', value);
        input.setAttribute('data-value', idValue);

        button.onclick = () => removeById(id);

        newDiv.id = id;
        newDiv.hidden = false;

        return newDiv;
    }

    function verifyRelation()
    {
        let uploader = document.getElementById('relation-uploader');
        let option = document.getElementById('relation-options').querySelector("option[value='" + uploader.value + "']");
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
        let duplicate = document.getElementById('relation-group').querySelector("input[value='" + uploader.value + "']");
        if (duplicate !== null) uploader.value = '';
        return duplicate === null;
    }
</script>
