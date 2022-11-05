<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Menu bar -->
<div class="container" style="margin-bottom: 1em;">
    <h1><?= $title ?></h1>
    <?= view('widgets/offcanvas_filters', ['properties' => $filters]) ?>
    <hr>
</div>

<!-- Presents all materials in a nicer html --->
<div class="parent-container d-flex">

<?= view('widgets/sidebar_filters', ['properties' => $filters]) ?>

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
<?= $this->endSection() ?>
