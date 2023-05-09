<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page__controls">
    <?= view('search_bar', ['action' => url_to('Admin\User::index'), 'options' => $options]) ?>
    <?= view('sort_bar', ['sorters' => ['Name', 'Email'], 'create' => 'userOpen()']); ?>
</div>

<div class="table" id="items">
<?php
    if ($users === []) {
        echo $this->include('none');
    } else foreach($users as $user) {
        echo view('admin/user/item', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
?>
</div>

<?= $pager->links('default', 'full') ?>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('admin/delete', ['action' => url_to('Admin\User::delete', 0)]) ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    <?php include_once(FCPATH . 'js/fetch.js'); ?>
    <?php include_once(FCPATH . 'js/modal.js') ?>

    const itemTemplate = `<?= json_encode(view('admin/user/item')) ?>`;
    const formTemplate = `<?= json_encode(view('admin/user/form', ['title' => null, 'submit' => null])) ?>`;

    const url = '<?= url_to('Admin\User::get', 0) ?>';
    const items = document.getElementById('items');

    const userOpen = async (id = undefined) => {
        const template = formTemplate.fill(id
            ? { title: 'Update user', submit: 'Update' }
            : {
                title: 'New user',
                submit: 'Create',
                id: "",
                name: "",
                email: ""
            }
        );
        modalOpen(id ? url.replace(/0$/, id) : undefined, template);
    }
</script>
<?= $this->endSection() ?>
