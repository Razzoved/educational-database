<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<form method="post" action="">
<div class="page">

    <aside class="page-sidebar">
        <div class="row g-0"><h1 style="opacity: 0"><?= $title ?></h1></div>
        <?= view('sidebar_checkboxes', ['properties' => $filters]) ?>
    </aside>

    <main class="page-content">
        <div class="page-controls">
            <div class="row g-0"><h1><?= $title ?></h1></div>
            <div class="row g-0" style="margin-bottom: 1rem">
                <input class="col form-control" name="search" value="" placeholder="Search"/>
                <button class="col-auto btn btn-success ms-2" style="width: 20%" type="submit">Search</button>
                <button type="button" class="icon" onclick="sidebar_toggle()">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
        </div>

        <div id="items">
        <?php
            foreach($materials as $material) {
                echo view_cell('\App\Libraries\Material::toCard', ['material' => $material]);
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