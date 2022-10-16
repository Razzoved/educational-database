<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>This is an E-learning materials page</h1>

<div class="container">
    <!-- Presents all materials in a nicer html --->
    <?php foreach($posts as $post) : ?>
        <?= view_cell('\App\Libraries\Material::postItem', ['post' => $post]); ?>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>