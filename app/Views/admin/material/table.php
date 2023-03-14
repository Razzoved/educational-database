<?= $this->extend('layouts/admin') ?>

<?= $this->section('header') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page">
    <div class="page-sidebar">
        <h1><?= $title ?></h1>
        <?= view('sidebar_checkboxes', ['properties' => $filters]) ?>
    </div>

    <main class="page-content">
        <h1><?= $title ?></h1>
        <div class="page-controls">
            <?= view('search_bar', ['action' => base_url('admin/materials/1')]) ?>
        </div>
        <div class="page-controls">
            <?= view('sort_bar', ['sorters' => ['ID', 'Title', 'Created at', 'Updated at', 'Views']]); ?>
            <button class="create" type="button" onclick="window.location.href='<?= base_url('admin/materials/edit') ?>'">&#65291</button>
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
    </main>
</div>
</form>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('admin/delete', ['action' => base_url('admin/materials/delete')]) ?>
<?= $this->endSection() ?>
