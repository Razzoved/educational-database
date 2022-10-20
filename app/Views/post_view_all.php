<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Menu bar -->
<div class="container" style="margin-bottom: 1em;">
    <h1><?= $title ?></h1>
    <?= $this->include('filtering') ?>
    <hr>
</div>

<!-- Presents all materials in a nicer html --->
<div class="container">
    <?php foreach($posts as $post) : ?>
        <?= view_cell('\App\Libraries\Material::postItem', ['post' => $post]) ?>
    <?php endforeach; ?>

    <!-- Paging PLACEHOLDER -->
    <ul class="pagination justify-content-center">
        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
        <li class="page-item"><a class="page-link" href="#">Next</a></li>
    </ul>

</div>

<?= $this->endSection() ?>