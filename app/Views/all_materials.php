<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Menu bar -->
<div class="container" style="margin-bottom: 5vh;">
    <h1><?= $title ?></h1>
    <?= $this->include('/widgets/search_bar') ?>
</div>

<!-- Presents all materials in a nicer html --->
<div class="container">
    <?php foreach($posts as $post) : ?>
        <?= view_cell('\App\Libraries\Material::postItem', ['post' => $post]); ?>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>