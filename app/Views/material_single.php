<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- START OF PAGE SPLIT --->
<div class="parent-container d-flex">

<!-- Post container -->
<div class="container bg-light m-0 border" style="min-height: 100vh">

    <!-- Post top view: img, header -->
    <div class="row g-0 mt-2">

        <!-- img -->
        <?= isset($material->referTo) ? "<a href='$material->referTo'>" : "" ?>
        <img class="col-sm-12 col-md-2 img-fluid rounded" style="object-fit:cover" alt="thumbnail"
             src=<?= $material->getThumbnail() ?>>
        <?= isset($material->referTo) ? "</a>" : "" ?>

        <!-- header: title, date, views, rating -->
        <header class="col ms-sm-0 ms-md-4" style="display: flex; flex-direction: column">

            <!-- title, goBack -->
            <div class="row g-0">
                <div class="col" style="max-width: 80%; word-wrap: break-word"><h1><?= $material->title ?></h1></div>
                <div class="col-auto d-none d-lg-block ms-auto"><a href='/' class="btn btn-dark">Go back</a></div>
            </div>

            <!-- date -->
            <p class="row p-1 text-muted"><small><?= $material->createdToDate() ?></small></p>

            <!-- rating, views -->
            <div class="row g-1 align-self-end mt-auto w-100" style="align-items: center; justify-content: center">
                <i class="col-auto bi bi-star-fill"></i>
                <i class="col-auto bi bi-star-fill"></i>
                <i class="col-auto bi bi-star-fill"></i>
                <i class="col-auto bi bi-star"></i>
                <i class="col-auto bi bi-star" style="margin-right: 0.2em"></i>
                <small class="col-auto" style="margin-right: 0.5em"><?= $material->rating?></small>
                <small class="col"><u><?= $material->rating_count?> ratings</u></small> <!-- TODO: implement this table and query -->
                <small class="col-auto text-muted">Viewed: <?= $material->views ?>x</small>
            </div>

        </header>
    </div>

    <!-- SMALL SCREEN -->
    <div>
    <span class="row g-0 d-md-block d-lg-none mt-2">
        <a class="btn btn-dark"
           data-bs-toggle="offcanvas"
           href="#offcanvasSidebar"
           aria-controls="offcanvasSidebar">
           Show all tags
        </a>
    </span>
    <span class="row g-0 d-md-block d-lg-none mt-2">
        <a href='/' class="btn btn-dark">Go back</a>
        </a>
    </span>
    </div>

    <hr>

    <!-- Content -->
    <div class="p-2">
        <pre style="white-space: pre-line; font-family: Sans-serif, arial, monospace; font-size: 1rem">
            <?= $material->content ?>
        </pre>
    </div>

    <!-- Links -->
    <div>
        <?= view_cell('App\Libraries\Material::getLinks', [$material]) ?>
    </div>

    <!-- Downloadable -->
    <div>
        <?= view_cell('App\Libraries\Material::getFiles', [$material]) ?>
    </div>

    <hr>

    <!-- Actions -->
    <div class="mb-5">
        <a href="/edit/<?= $material->post_id ?>" class="btn btn-primary">Edit</a>
        <a href="/delete/<?= $material->post_id ?>" class="btn btn-danger">Delete</a>
        <a href='/' class="btn btn-info">Back to main page</a>
    </div>
</div>

<!-- Padding -->
<div class="bg-white d-none d-lg-inline" style="height: 100vh; width: 10px;">
</div>

<!-- Tags -->
<?php
    $properties = $material->getGroupedProperties();
    echo view('widgets/sidebar_buttons', [$properties]);
    echo view('widgets/offcanvas_buttons', [$properties]);
?>

</div>
<!-- END OF PAGE SPLIT -->

<?= $this->endSection() ?>
