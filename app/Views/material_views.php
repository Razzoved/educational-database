<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="page">

    <div class="page__content">
        <h1 class="page__title"><?= $title ?></h1>

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
    </div>

</div>
<?= $this->endSection() ?>
