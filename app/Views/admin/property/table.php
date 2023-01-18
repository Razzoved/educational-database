<?= $this->extend('layouts/admin') ?>

<?= $this->section('header') ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page">

    <div class="page-sidebar">
        <form method="post" action="<?= base_url('admin/tags/1') ?>">
            <div class="row g-0" style="margin-bottom: 1rem">
                <input class="col form-control" name="search" value="" placeholder="Search"/>
                <button class="col-auto btn btn-dark ms-2" style="width: fit-content" type="submit">Search</button>
            </div>
            <?= view('widgets/sidebar_checkboxes', ['properties' => $filters]) ?>
        </form>
    </div>

    <div class="page-content">
        <div style="width: 100%; display: inline-flex; margin-bottom: 1rem;">
            <input id="tag" type="text" class="form-control me-2" placeholder="Tag">
            <input id="value" type="text" class="form-control me-2" placeholder="Value">
            <button type="button" style="width: 50%" class="btn btn-success" onclick="createProperty()">Create</button>
        </div>

        <div class="bg-dark text-bg-dark rounded" style="display: flex; text-align: center; white-space: nowrap; margin-bottom: 0.8rem; justify-content: space-evenly">
            <button type="button" onclick="toggleSort('id')" class="ms-1 me-1 btn btn-dark"><i class="fa-solid <?= isset($_POST['sort']) && $_POST['sort'] === 'id' ? ($_POST['sortDir'] === 'DESC' ? 'fa-caret-up' : 'fa-caret-down') : 'fa-caret-right' ?>"></i> ID</button>
            <button type="button" onclick="toggleSort('tag')" class="ms-1 me-1 btn btn-dark"><i class="fa-solid <?= isset($_POST['sort']) && $_POST['sort'] === 'tag' ? ($_POST['sortDir'] === 'DESC' ? 'fa-caret-up' : 'fa-caret-down') : 'fa-caret-right' ?>"></i> Tag</button>
            <button type="button" onclick="toggleSort('value')" class="ms-1 me-1 btn btn-dark"><i class="fa-solid <?= isset($_POST['sort']) && $_POST['sort'] === 'value' ? ($_POST['sortDir'] === 'DESC' ? 'fa-caret-up' : 'fa-caret-down') : 'fa-caret-right' ?>"></i> Value</button>
            <!-- TODO: <button type="button" onclick="toggleSort('usage')" class="d-none d-md-inline ms-1 me-1 btn btn-dark"><i class="fa-solid fa-caret-right"></i> Usage</button> -->
        </div>

        <div id="items">
        <?php
            echo view_cell('\App\Libraries\Property::getRowTemplate');
            $index = 0;
            foreach($properties as $property) {
                echo view_cell('\App\Libraries\Property::toRow', ['property' => $property, 'index' => $index++]);
            }
        ?>
        </div>

        <?= $pager->links('default', 'full') ?>
    </div>
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
            error: function(status) {
                alert('TODO: modal\nUnable to create tag -> ' + status.statusText);
            },
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
        template.querySelector('[data-value=id]').innerHTML = data.id;
        template.querySelector('[data-value=tag]').innerHTML = data.tag;
        template.querySelector('[data-value=value]').innerHTML = data.value;
        template.querySelector('[data-value=usage]').innerHTML = 0;
        let del = template.querySelector('button[onclick^=deleteOpen]');
        del.setAttribute("onclick", `deleteOpen("${data.id}")`);
        document.getElementById('items').appendChild(template);
    }
</script>
<?= $this->endSection() ?>
