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
<div class="parent-container d-flex">

    <?= view('widgets/sidebar_checkboxes', ['properties' => $filters]) ?>
    <?= view('widgets/offcanvas_checkboxes', ['properties' => $filters]) ?>

    <!-- Padding -->
    <div class="bg-white d-none d-lg-inline" style="height: 100vh; width: 10px;">
    </div>

    <!-- Contents -->
    <div class="container-fluid">
        <?php foreach($posts as $post) : ?>
            <?= view_cell('\App\Libraries\Post::postItem', ['post' => $post]) ?>
        <?php endforeach; ?>

        <!-- Paging PLACEHOLDER -->
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>

    </div>
</div>

</form>
<?= $this->endSection() ?>
