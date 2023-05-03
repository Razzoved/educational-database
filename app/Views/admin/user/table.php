<?= $this->extend('layouts/admin') ?>

<?= $this->section('header') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page">
    <h1 class="page__title"><?= $title ?></h1>

    <div class="page__content">
        <div class="page-controls">
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
                ], ['saveData' => false]);
            }
        ?>
        </div>

        <?= $pager->links('default', 'full') ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('admin/delete', ['action' => url_to('Admin\User::delete', 0)]) ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    <?php include_once(FCPATH . 'js/modal.js') ?>

    const formTemplate = `<?= json_encode(view('admin/user/form', ['title' => null, 'submit' => null])) ?>`;
    const itemTemplate = `<?= json_encode(view('admin/user/item')) ?>`;

    const url = '<?= url_to('Admin\User::get', 0) ?>';
    const items = document.getElementById('items');

    const userOpen = async (id = undefined) => {
        const template = formTemplate.fill(id
            ? { title: 'Update user', submit: 'Update' }
            : { title: 'New user', submit: 'Create', id: "", name: "", email: "" }
        );
        modalOpen(id ? url.replace(/0$/, id) : undefined, template);
    }
</script>
<?= $this->endSection() ?>
