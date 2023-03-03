<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<form method="post" action="">
<div class="page">

    <main class="page-content">
        <h1><?= $title ?></h1>

        <div id="items">
        <?php
            foreach($materials as $material) {
                echo view_cell('\App\Libraries\Material::toCard', ['material' => $material]);
            }
            if ($materials === []) {
                echo '<hr style="margin-top: 1rem; margin-bottom: 1rem">';
                echo '<h2 style="text-align:center">None were found.</h2>';
            }
        ?>
        </div>
    </main>

</div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // prevent resubmit on page
    if (window.history.replaceState) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<?= $this->endSection() ?>
