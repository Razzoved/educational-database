<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<form method="post" action="">
<div class="page">

    <div class="page-sidebar">
        <h1><?= $title ?></h1>
        <?= view('sidebar_checkboxes', ['properties' => $filters]) ?>
    </div>

    <main class="page-content">
        <h1><?= $title ?></h1>

        <div class="page-controls">
            <input name="search" value="" placeholder="Search"/>
            <button type="submit">Search</button>
        </div>

        <div id="items">
        <?php
            foreach($materials as $material) {
                echo view('components/material_as_card', ['material' => $material]);
            }
        ?>
        </div>

        <?= $pager->links('default', 'full') ?>
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
