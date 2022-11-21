<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<form method="post" action="/">

<!-- Menu bar -->
<div class="container-fluid g-0 mb-4">
    <div class="row g-0 ms-4">
        <div class="d-none d-lg-inline" style="width: 280px"></div>
        <div class="col"><h1><?= $title ?></h1></div>
    </div>

    <div class="row g-0 ms-4">
        <div class="d-none d-lg-inline" style="width: 280px"></div>
        <div class="col">
            <input class="form-control" name="search" value="" placeholder="Search"/>
        </div>
        <div class="col-auto me-2 me-lg-0 ms-2" style="width: 20%">
            <button class="btn btn-success w-100" type="submit">Search</button>
        </div>
        <div class="col-auto d-block d-lg-none" style="width: 20%">
            <a class="btn btn-dark w-100" data-bs-toggle="offcanvas" href="#offcanvasSidebar" aria-controls="offcanvasSidebar">Filters</a>
        </div>
    </div>
</div>

<!-- Presents all materials in a nicer html --->
<div class="parent-container d-flex vh100">

    <?= view('widgets/sidebar_checkboxes', ['properties' => $filters]) ?>
    <?= view('widgets/offcanvas_checkboxes', ['properties' => $filters]) ?>

    <!-- Contents -->
    <div class="container-fluid g-0 ms-4">
        <?php
            if ($materials == []) {
                echo '<div class="text-center">';
                echo '<br>';
                echo '<hr>';
                echo '<h1>Nothing was found!</h1>';
                echo '<hr>';
                echo '<br>';
                echo '</div>';
            } else {
                foreach($materials as $material) {
                    echo view_cell('\App\Libraries\Material::toCard', ['material' => $material]);
                }
                // paging
                $prevPage = max($page - 1, 0);
                $nextPage = $page + 1;

                echo '<nav aria-label="pagesLabel">';
                echo '<ul class="pagination justify-content-center">';
                if ($page == 0) {
                    echo '<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">Previous</a></li>';
                } else {
                    echo "<li class='page-item'><a class='page-link' href='/$prevPage'>Previous</a></li>";
                }
                echo "<li class='page-item'><a class='page-link' href='#'>$page</a></li>";
                echo "<li class='page-item'><a class='page-link' href='/$nextPage'>Next</a></li>";
                echo '</ul>';
                echo '</nav>';
            }
        ?>

    </div>
</div>

</form>
<?= $this->endSection() ?>
