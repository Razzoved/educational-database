<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<form method="post" action="/">

<!-- Menu bar -->
<div class="container" style="margin-bottom: 1em;">
    <h1><?= $title ?></h1>

    <div class="row">
        <div class="col" style="margin-right:-1em">
            <input class="form-control" name="search" value="" placeholder="Search"/>
        </div>
        <div class="col-auto">
            <button class="btn btn-dark" type="submit">Search</button>
        </div>
        <div class="col-auto">
            <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvasSidebar" aria-controls="offcanvasSidebar">Filters</a>
        </div>
    </div>

    <hr>
</div>

<!-- Presents all materials in a nicer html --->
<div class="parent-container d-flex" style="height:100%">

    <?= view('widgets/sidebar_checkboxes', ['properties' => $filters]) ?>
    <?= view('widgets/offcanvas_checkboxes', ['properties' => $filters]) ?>

    <!-- Padding -->
    <div class="bg-white d-none d-lg-inline" style="height: 100%; width: 10px;"></div>

    <!-- Contents -->
    <div class="container-fluid border p-2 bg-light">
        <?php
            if ($posts == []) {
                echo '<div class="text-center h-50"><h5>Nothing was found!</h5></div>';
            } else {
                foreach($posts as $post) {
                    echo view_cell('\App\Libraries\Post::postItem', ['post' => $post]);
                }
                // paging
                $prevPage = max($page - 1, 0);
                $nextPage = $page + 1;
                echo '<ul class="pagination justify-content-center">';
                echo "<li class='page-item'><a class='page-link' href='/$prevPage'>Previous</a></li>";
                echo "<li class='page-item'><a class='page-link' href='/$nextPage'>Next</a></li>";
                echo '</ul>';
            }
        ?>

    </div>
</div>

</form>
<?= $this->endSection() ?>
