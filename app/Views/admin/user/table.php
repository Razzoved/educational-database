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
            if ($users === []) {
                echo '<hr style="margin-top: 1rem; margin-bottom: 1rem">';
                echo '<h2 style="text-align:center">None were found.</h2>';
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
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('admin/delete', ['action' => url_to('Admin\User::delete', 0)]) ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
</script>
<?= $this->endSection() ?>
