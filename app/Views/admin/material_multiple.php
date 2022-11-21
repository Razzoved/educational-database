<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<form method="post" action="/">

<div class="parent-container d-flex p-4">

    <table class="table table-striped table-bordered g-0 me-4" style="vertical-align: middle; height: fit-content">
        <thead clas="table-dark">
            <th scope="col"><input type="checkbox" id="materials_bulk"></th>
            <th scope="col">Title</th>
            <th scope="col">Thumbnail</th>
            <th scope="col">Date</th>
            <th scope="col">Views</th>
            <th scope="col" colspan="2">Ratings</th>
        </thead>
        <tbody>
        <?php
            foreach($entities as $material) {
                echo view_cell('\App\Libraries\Material::toItem', ['material' => $material]);
            }
        ?>
        </tbody>
    </table>

    <?= view('widgets/sidebar_checkboxes', ['properties' => $otherData]) ?>
</div>

</form>
<?= $this->endSection() ?>
