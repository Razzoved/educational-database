<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container" style="margin-bottom: 5vh;">
    <h1>Materials</h1>
    <?= $this->include('/widgets/search_bar') ?>
</div>

<div class="container">
    <!-- Presents all materials in a nicer html --->
    <?php foreach($posts as $post) : ?>
        <?= view_cell('\App\Libraries\Material::postItem', ['post' => $post]); ?>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>