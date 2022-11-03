<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>


<div class="parent-container d-flex"><!-- START OF PAGE SPLIT --->

<!-- Post container -->
<div class="container" style="margin-bottom: 5vh;">

    <!-- Post top view: img, header -->
    <div class="row g-0 m-1">

        <!-- img -->
        <?= isset($post->referTo) ? "<a href='$post->referTo'>" : "" ?>
        <img class="col-sm-12 col-md-4 img-fluid rounded float-left m-2" alt="thumbnail"
             src=<?= $post->post_thumbnail ?>>
        <?= isset($post->referTo) ? "</a>" : "" ?>

        <!-- header: title, date, views, rating -->
        <header class="col bg-light m-2" style="align-items: center; justify-content: center; margin-bottom: 1vh;">
            <a href='/' class="btn btn-info">Back to main page</a>
            <h1><?= $post->post_title ?></h1>
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

<!-- Tags sidebar shown ONLY on lg -->
<div class="flex-shrink-0 p-3 bg-light d-none d-lg-inline" style="width: 280px;">
    <ul class="list-unstyled ps-0">
        <?php foreach ($post->getGroupedProperties() as $k => $v) : ?>
            <?= view_cell('App\Libraries\Property::postGroup', ['tag' => $k, 'values' => $v]) ?>
        <?php endforeach; ?>
    </ul>
</div>

</div><!-- END OF PAGE SPLIT -->

<?= $this->endSection() ?>
