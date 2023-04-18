<?= $this->extend('layouts/admin') ?>

<?= $this->section('header') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page">

    <div class="page__content">
        <h1 class="page__title"><?= $title ?></h1>

        <div class="page-controls">
            <?= view('search_bar', ['action' => url_to('Admin\User::index'), 'options' => $options]) ?>
            <?= view('sort_bar', ['sorters' => ['Name', 'Email'], 'create' => 'userOpen()']); ?>
        </div>

        <div class="table" id="items">
        <?php
            echo view_cell('\App\Libraries\User::getRowTemplate');
            $index = 0;
            foreach($users as $user) {
                echo view_cell('\App\Libraries\User::toRow', ['user' => $user, 'index' => $index++]);
            }
        ?>
        </div>

        <?= $pager->links('default', 'full') ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('admin/delete', ['action' => url_to('Admin\User::delete', 0), 'idName' => 'email']) ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function userOpen(id = undefined)
    {
        userEdit = false;
        passwordNote.hidden = false;

        if (id !== undefined) {
            $.ajax({
                type: 'GET',
                url: '<?= url_to('Admin\User::get', $id) ?>',
                data: { id },
                dataType: 'json',
                success: function(result) {
                    result = (result.id === undefined)
                        ? JSON.parse(result)
                        : result;
                    userEdit = true;
                    passwordNote.hidden = false;
                    modalOpen('user-window', result);
                },
                error: (jqHXR) => showError(jqHXR)
            });
        } else {
            modalOpen('user-window', []);
        }
    }

    function updateData(data)
    {
        let element = document.getElementById(`${data.email}`);
        if (element === undefined || element === null) {
            console.info(`Element of id: '${data.email}' not found for update`);
            return;
        }
        element.querySelector('[data-value=name]').innerHTML = data.name;
        element.querySelector('[data-value=email]').innerHTML = data.email;
    }

    function appendData(data)
    {
        let template = document.getElementById('template');
        if (template === undefined || !template.hasChildNodes()) {
            console.warn('No template found. Cannot append data to table!');
            return;
        }
        template = template.firstElementChild.cloneNode(true);
        template.id = data.email;
        template.querySelector('[data-value=name]').innerHTML = data.name;
        template.querySelector('[data-value=email]').innerHTML = data.email;
        let edit = template.querySelector('button[onclick^=userOpen]');
        let del = template.querySelector('button[onclick^=deleteOpen]');
        edit.setAttribute("onclick", `userOpen("${data.email}")`);
        del.setAttribute("onclick", `deleteOpen("${data.email}")`);
        document.getElementById('items').appendChild(template);
    }
</script>
<?= $this->endSection() ?>
