<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<form method="post" action="/">

<div class="d-flex p-4">

    <table class="table table-striped g-0 me-4" style="vertical-align: middle; height: fit-content">
        <thead clas="table-dark">
            <th scope="col" style="width: 30px; height: 30px"><input type="checkbox" id="materials_bulk" style="width: 20px; height: 20px"></th>
            <th scope="col">Thumbnail</th>
            <th scope="col">Title</th>
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
