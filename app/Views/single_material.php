<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Menu bar -->
<div class="container" style="margin-bottom: 5vh;">
    <?= isset($title) ? "<h1>Material with id: $title</h1>" : "<h1>Material not found</h1>" ?>
    <?= isset($post) ? "<a href=\"/edit/$post[post_id]\" class=\"btn btn-primary\">Edit</a>" : "" ?>
    <?= isset($post) ? "<a href=\"/delete/$post[post_id]\" class=\"btn btn-danger\">Delete</a>" : "" ?>
</div>

<?= $this->endSection() ?>