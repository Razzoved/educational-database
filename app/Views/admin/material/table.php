<?= $this->extend('layouts/admin') ?>

<?= $this->section('header') ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page">

    <div class="page-sidebar">
        <form method="post" action="<?= base_url('admin/materials/1') ?>">
            <div class="row g-0" style="margin-bottom: 1rem">
                <input class="col form-control" name="search" value="" placeholder="Search"/>
                <button class="col-auto btn btn-dark ms-2" style="width: fit-content" type="submit">Search</button>
            </div>
            <?= view('sidebar_checkboxes', ['properties' => $filters]) ?>
        </form>
    </div>

    <div class="page-content">
        <form method="post" action="<?= base_url('admin/materials/1') ?>">
            <div class="row g-0 d-inline-flex d-lg-none" style="width: 100%; margin-bottom: 1rem">
                <input class="col form-control" name="search" value="" placeholder="Search"/>
                <button class="col-auto btn btn-dark ms-2" style="width: 20%" type="submit">Search</button>
                <a class="col-auto btn btn-dark ms-2" style="width: 20%" data-bs-toggle="offcanvas" href="#offcanvasSidebar" aria-controls="offcanvasSidebar">Filters</a>
                <?= view('offcanvas_checkboxes', ['properties' => $filters]) ?>
            </div>
        </form>

        <div style="display: flex; margin-bottom: 1rem; justify-content: space-between">
            <div class="bg-dark text-bg-dark rounded" style="display: flex; width: 100%; justify-content: space-evenly">
                <button type="button" onclick="toggleSort('id')" class="ms-1 me-1 btn btn-dark"><i class="fa-solid <?= isset($_POST['sort']) && $_POST['sort'] === 'id' ? ($_POST['sortDir'] === 'DESC' ? 'fa-caret-up' : 'fa-caret-down') : 'fa-caret-right' ?>"></i> ID</button>
                <button type="button" onclick="toggleSort('title')" class="ms-1 me-1 btn btn-dark"><i class="fa-solid <?= isset($_POST['sort']) && $_POST['sort'] === 'title' ? ($_POST['sortDir'] === 'DESC' ? 'fa-caret-up' : 'fa-caret-down') : 'fa-caret-right' ?>"></i> Title</button>
                <button type="button" onclick="toggleSort('created_at')" class="d-none d-xl-inline me-1 btn btn-dark"><i class="fa-solid <?= isset($_POST['sort']) && $_POST['sort'] === 'created_at' ? ($_POST['sortDir'] === 'DESC' ? 'fa-caret-up' : 'fa-caret-down') : 'fa-caret-right' ?>"></i> Created At</button>
                <button type="button" onclick="toggleSort('updated_at')" class="me-1 btn btn-dark"><i class="fa-solid <?= isset($_POST['sort']) && $_POST['sort'] === 'updated_at' ? ($_POST['sortDir'] === 'DESC' ? 'fa-caret-up' : 'fa-caret-down') : 'fa-caret-right' ?>"></i> Last Update</button>
                <button type="button" onclick="toggleSort('views')" class="ms-1 me-1 btn btn-dark"><i class="fa-solid <?= isset($_POST['sort']) && $_POST['sort'] === 'views' ? ($_POST['sortDir'] === 'DESC' ? 'fa-caret-up' : 'fa-caret-down') : 'fa-caret-right' ?>"></i> Views</button>
                <!-- TODO: <button type="button" onclick="toggleSort('rating')" class="ms-1 me-1 btn btn-dark"><i class="fa-solid fa-caret-right"></i> Rating</button> -->
            </div>
            <a type="button" href="<?= base_url('admin/materials/edit') ?>" class="ms-1 me-1 btn btn-primary">&#65291</a>
        </div>

        <div id="items">
        <?php
            echo view_cell('\App\Libraries\Material::getRowTemplate');
            $index = 0;
            foreach($materials as $material) {
                echo view_cell('\App\Libraries\Material::toRow', ['material' => $material, 'index' => $index++]);
            }
        ?>
        </div>

        <?= $pager->links('default', 'full') ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('admin/delete', ['action' => base_url('admin/materials/delete')]) ?>
<?= $this->endSection() ?>
