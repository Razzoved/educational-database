<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<form method="post" action="">

<div class="page">
    <?= view('widgets/offcanvas_checkboxes', ['properties' => $filters]) ?>

    <div class="page-sidebar">
        <div class="row g-0"><h1 style="opacity: 0"><?= $title ?></h1></div>
        <?= view('widgets/sidebar_checkboxes', ['properties' => $filters]) ?>
    </div>

    <div class="page-content">
        <div class="row g-0"><h1><?= $title ?></h1></div>
        <div class="row g-0" style="margin-bottom: 1rem">
            <input class="col form-control" name="search" value="" placeholder="Search"/>
            <button class="col-auto btn btn-success ms-2" style="width: 20%" type="submit">Search</button>
            <a class="col-auto btn btn-dark d-block d-lg-none ms-2" style="width: 20%" data-bs-toggle="offcanvas" href="#offcanvasSidebar" aria-controls="offcanvasSidebar">Filters</a>
        </div>

        <div id="items">
        <?php
            foreach($materials as $material) {
                echo view_cell('\App\Libraries\Material::toCard', ['material' => $material]);
            }
        ?>
        </div>

        <?= $pager->links('default', 'full') ?>
    </div>
</div>

</form>

<script>
    // prevent resubmit on page
    if (window.history.replaceState) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<?= $this->endSection() ?>
