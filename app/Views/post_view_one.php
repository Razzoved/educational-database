<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- START OF PAGE SPLIT --->
<div class="parent-container d-flex">

<!-- Post container -->
<div class="container bg-light m-0" style="height: 100vh">

    <!-- Post top view: img, header -->
    <div class="row g-0 mt-2">

        <!-- img -->
        <?= isset($post->referTo) ? "<a href='$post->referTo'>" : "" ?>
        <img class="col-sm-12 col-md-4 img-fluid rounded float-left" alt="thumbnail"
             src=<?= $post->post_thumbnail ?>>
        <?= isset($post->referTo) ? "</a>" : "" ?>

        <!-- header: title, date, views, rating -->
        <header class="col p-3" style="align-items: center; justify-content: center">

            <!-- title, goBack -->
            <div class="row g-0">
                <div class="col"><h1><?= $post->post_title ?></h1></div>
                <div class="col-auto d-none d-md-inline"><a href='/' class="btn btn-dark">Go back</a></div>
            </div>

            <!-- date -->
            <small ><p class="text-muted"><?= date_format($post->created_at, "d.m.Y") ?></p></small>

            <div class="row-auto" style="align-items: center; justify-content: center">
            </div>

            <!-- rating, views -->
            <div class="row g-1" style="align-items: center; justify-content: center">
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

    <!-- Tags if screen too small -->

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
    <div>
        <a href="/edit/<?= $post->post_id ?>" class="btn btn-primary">Edit</a>
        <a href="/delete/<?= $post->post_id ?>" class="btn btn-danger">Delete</a>
        <a href='/' class="btn btn-info">Back to main page</a>
    </div>
</div>

<!-- Padding -->
<div class="bg-white d-none d-lg-inline" style="height: 100vh; width: 10px;">
</div>

<!-- Tags sidebar shown ONLY on lg -->
<div class="flex-shrink-0 p-3 bg-light d-none d-lg-inline" style="height: 100vh; width: 280px;">
    <ul class="list-unstyled ps-0">
        <?php foreach ($post->getGroupedProperties() as $k => $v) : ?>
            <?= view_cell('App\Libraries\Property::postGroup', ['tag' => $k, 'values' => $v]) ?>
        <?php endforeach; ?>
    </ul>
</div>

</div>
<!-- END OF PAGE SPLIT -->

<?= $this->endSection() ?>
