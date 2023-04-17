<?= $this->extend('layouts/admin') ?>

<?= $this->section('header') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page">
    <div class="page__sidebar">
        <h1 class="page__title"><?= $title ?></h1>
        <?= view('sidebar_checkboxes', ['properties' => $filters]) ?>
    </div>

    <div class="page__content">
        <h1 class="page__title"><?= $title ?></h1>
        <div class="page-controls">
            <?= view('search_bar', ['action' => url_to('Admin\Material::index'), 'options' => $options]) ?>
            <?= view('sort_bar', [
                'sorters' => ['ID', 'Title', 'Created at', 'Updated at', 'Views'],
                'create' => "window.location.href='" . url_to('Admin\MaterialEditor::index') . "'"]) ?>
        </div>

        <div class="table" id="items">
        <?php
            echo view_cell('\App\Libraries\Material::getRowTemplate');
            $index = 0;
            foreach($materials as $material) {
                echo view_cell('\App\Libraries\Material::toRow', ['material' => $material, 'index' => $index++]);
            }
            if ($materials === []) {
                echo '<hr style="margin-top: 1rem; margin-bottom: 1rem">';
                echo '<h2 style="text-align:center">None were found.</h2>';
            }
            ?>
        </div>

        <?= $pager->links('default', 'full') ?>
    </div>
</div>
</form>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('admin/delete', ['action' => url_to('Admin\MaterialEditor::delete', 0)]) ?>
<?= $this->endSection() ?>
