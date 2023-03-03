<?= $this->extend('layouts/admin') ?>

<?= $this->section('header') ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page">

    <main class="page-content">
        <h1><?= $title ?></h1>
        <form method="post" action="<?= base_url('admin/users/1') ?>">
            <div class="page-controls">
                <input class="col form-control" name="search" value="" placeholder="Search"/>
                <button class="col-auto btn btn-dark ms-2" style="width: 10vw; min-width: fit-content" type="submit">Search</button>
            </div>
        </form>

        <div class="page-controls">
            <button type="button" onclick="toggleSort('name')"><i class="fa-solid <?= isset($_POST['sort']) && $_POST['sort'] === 'name' ? ($_POST['sortDir'] === 'DESC' ? 'fa-caret-up' : 'fa-caret-down') : 'fa-caret-right' ?>"></i> Name</button>
            <button type="button" onclick="toggleSort('email')"><i class="fa-solid <?= isset($_POST['sort']) && $_POST['sort'] === 'email' ? ($_POST['sortDir'] === 'DESC' ? 'fa-caret-up' : 'fa-caret-down') : 'fa-caret-right' ?>"></i> Email</button>
            <button type="button" onclick="userOpen()" class="ms-1 me-1 btn btn-primary">&#65291</a>
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
    </main>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('admin/delete', ['action' => base_url('admin/users/delete'), 'idName' => 'email']) ?>
<?= view('admin/user/form', ['title' => 'User form', 'submit' => 'Save']) ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
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
