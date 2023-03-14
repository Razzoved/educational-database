<?= $this->extend('layouts/admin') ?>

<?= $this->section('header') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page">

    <div class="page-sidebar">
        <h1><?= $title ?></h1>
        <input id="tag" type="text" placeholder="Tag">
        <input id="value" type="text" placeholder="Value">
        <button class="create" type="button" style="width: 50%" onclick="createProperty()">Create</button>
        <?= view('sidebar_checkboxes', ['properties' => $filters]) ?>
    </div>

    <main class="page-content">
        <h1><?= $title ?></h1>

        <div class="page-controls">
            <?= view('search_bar') ?>
        </div>

        <div class="page-controls">
            <?= view('sort_bar', ['sorters' => ['Id', 'Tag', 'Value']]) ?>
        </div>

        <div class="table" id="items">
        <?php
            echo view_cell('\App\Libraries\Property::getRowTemplate');
            $index = 0;
            foreach($properties as $property) {
                echo view_cell('\App\Libraries\Property::toRow', ['property' => $property, 'index' => $index++]);
            }
            if ($properties === []) {
                echo '<hr style="margin-top: 1rem; margin-bottom: 1rem">';
                echo '<h2 style="text-align:center">None were found.</h2>';
            }
            ?>
        </div>

        <?= $pager->links('default', 'full') ?>
    </main>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('admin/delete', ['action' => base_url('admin/tags/delete')]) ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function createProperty()
    {
        let tagger = document.getElementById('tag');
        let valuator = document.getElementById('value');

        $.ajax({
            url: '<?= base_url('admin/tags/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                tag: tagger.value,
                value: valuator.value,
            },
            success: function(result) {
                tagger.value = '';
                valuator.value = '';
                if (result.id === undefined) result = JSON.parse(result);
                appendData(result);
            },
            error: (jqHXR) => showError(jqHXR)
        });
    }

    function appendData(data)
    {
        let template = document.getElementById('template');
        if (template === undefined || !template.hasChildNodes()) {
            console.warn('No template found. Cannot append data to table!');
            return;
        }
        template = template.firstElementChild.cloneNode(true);
        template.id = data.id;
        template.querySelector('[data-value=id]').innerHTML = data.id;
        template.querySelector('[data-value=tag]').innerHTML = data.tag;
        template.querySelector('[data-value=value]').innerHTML = data.value;
        let usg = template.querySelector('[data-value=usage]');
        let edt = template.querySelector('a');
        let del = template.querySelector('button[onclick^=deleteOpen]');
        usg.setAttribute('innerHTML', usg.innerHTML.replace(/[0-9]+$/, 0));
        edt.setAttribute('href', edt.href.replace(/[0-9]+$/, data.id));
        del.setAttribute("onclick", `deleteOpen("${data.id}")`);
        document.getElementById('items').appendChild(template);
    }
</script>
<?= $this->endSection() ?>
