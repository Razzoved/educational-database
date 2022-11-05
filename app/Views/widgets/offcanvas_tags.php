<?= $this->extend('layouts/offcanvas_list') ?>

<?= $this->section('visible') ?>
    <span>
        <a class="btn btn-dark"
           data-bs-toggle="offcanvas"
           href="#filterSidebar"
           aria-controls="filterSidebar">
           Show tags
        </a>
    </span>
<?= $this->endSection() ?>

<?= $this->section('offcanvas_header') ?>
    <h5 class="offcanvas-title" id="offcanvasSidebarLabel">Tags</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
<?= $this->endSection() ?>

<?= $this->section('offcanvas_body') ?>
    <?= $this->include('component_extensions/collapsible_property_btn', ['properties' => $properties]) ?>
<?= $this->endSection() ?>
