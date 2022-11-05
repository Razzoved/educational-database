<?= $this->extend('layouts/offcanvas_list') ?>

<form method="post" action="/">

<?= $this->section('visible') ?>
    <div class="col" style="margin-right:-1em">
        <input class="form-control" name="search" value="" placeholder="Search"/>
    </div>
    <div class="col">
        <a class="btn btn-primary" data-bs-toggle="offcanvas" href="#offcanvasSidebar" aria-controls="offcanvasSidebar">Filters</a>
    </div>
<?= $this->endSection() ?>

<?= $this->section('offcanvas_header') ?>
    <h5 class="offcanvas-title" id="offcanvasSidebarLabel">Filters</h5>
    <button class="btn btn-success">Search</button>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
<?= $this->endSection() ?>

<?= $this->section('offcanvas_body') ?>
    <?= view('component_extensions/collapsible_property_box', ['properties' => $properties]) ?>
<?= $this->endSection() ?>

</form>
