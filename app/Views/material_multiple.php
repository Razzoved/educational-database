<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="page">

    <div class="page-sidebar">
        <h1><?= $title ?></h1>
        <?= view('sidebar_checkboxes', ['properties' => $filters]) ?>
    </div>

    <main class="page-content">
        <h1><?= $title ?></h1>

        <div class="page-controls">
            <?= view('search_bar') ?>
        </div>

        <div id="items">
        <?php
            foreach($materials as $material) {
                echo view('components/material_as_card', ['material' => $material]);
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
<?= $this->endSection() ?>
