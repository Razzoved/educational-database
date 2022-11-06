<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- START OF PAGE SPLIT --->
<div class="parent-container d-flex">

<!-- Post container -->
<div class="container bg-light m-0" style="min-height: 100vh">

    <!-- Post top view: img, header -->
    <div class="row g-0 mt-2">

        <!-- img -->
        <?= isset($post->referTo) ? "<a href='$post->referTo'>" : "" ?>
        <img class="col-sm-12 col-md-4 img-fluid rounded" alt="thumbnail"
             src=<?= $post->post_thumbnail ?>>
        <?= isset($post->referTo) ? "</a>" : "" ?>

        <!-- header: title, date, views, rating -->
        <header class="col ms-sm-0 ms-md-2" style="display: flex; flex-direction: column">

            <!-- title, goBack -->
            <div class="row g-0">
                <div class="col"><h1><?= $post->post_title ?></h1></div>
                <div class="col-auto ms-2 d-none d-md-inline"><a href='/' class="btn btn-dark">Go back</a></div>
            </div>

            <!-- date -->
            <p class="row p-1 text-muted"><small><?= $post->createdToDate() ?></small></p>

            <!-- rating, views -->
            <div class="row g-1 align-self-end mt-auto w-100" style="align-items: center; justify-content: center">
                <i class="col-auto bi bi-star-fill"></i>
                <i class="col-auto bi bi-star-fill"></i>
                <i class="col-auto bi bi-star-fill"></i>
                <i class="col-auto bi bi-star"></i>
                <i class="col-auto bi bi-star" style="margin-right: 0.2em"></i>
                <small class="col-auto" style="margin-right: 0.5em"><?= $post->post_rating?></small>
                <small class="col"><u><?= $post->post_rating?> ratings</u></small> <!-- TODO: implement this table and query -->
                <small class="col-auto text-muted">Viewed: <?= $post->post_views ?>x</small>
            </div>

        </header>
    </div>

    <!-- SMALL SCREEN -->
    <span class="row d-sm-inline d-md-none m-2">
        <a class="btn btn-dark"
           data-bs-toggle="offcanvas"
           href="#offcanvasSidebar"
           aria-controls="offcanvasSidebar">
           Show all tags
        </a>
    </span>
    <span class="row d-sm-inline d-md-none m-2">
        <a href='/' class="btn btn-dark">Go back</a>
        </a>
    </span>

    <hr>

    <!-- Content -->
    <div>
        <p><?= $post->post_content ?></p>
    </div>

    <hr>

    <!-- Materials -->
    <div>
        <?= view_cell('App\Libraries\Material::getMaterialsList', ['post' => $post]) ?>
    </div>

    <hr>

    <!-- Actions -->
    <div class="mb-2">
        <a href="/edit/<?= $post->post_id ?>" class="btn btn-primary">Edit</a>
        <a href="/delete/<?= $post->post_id ?>" class="btn btn-danger">Delete</a>
        <a href='/' class="btn btn-info">Back to main page</a>
    </div>
</div>

<!-- Padding -->
<div class="bg-white d-none d-lg-inline" style="height: 100vh; width: 10px;">
</div>

<!-- Tags -->
<?php
    $properties = $post->getGroupedProperties();
    echo view('widgets/sidebar_buttons', ['properties' => $properties]);
    echo view('widgets/offcanvas_buttons', ['properties' => $properties]);
?>

</div>
<!-- END OF PAGE SPLIT -->

<?= $this->endSection() ?>
