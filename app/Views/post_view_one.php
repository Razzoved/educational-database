<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Menu bar -->
<div class="container" style="margin-bottom: 5vh;">
    <?= isset($title) ? "<h1>$title</h1>" : "<h1>Material not found</h1>" ?>
    <?= isset($post['post_thumbnail']) ? '<img src="' . $post['post_thumbnail'] . 'alt="missing image">' : "" ?>
    <?= isset($post['post_content']) ? "<p>" . $post['post_content'] . "</p>" : "" ?>
    <?= isset($post) ? "<a href=\"/edit/$post[post_id]\" class=\"btn btn-primary\">Edit</a>" : "" ?>
    <?= isset($post) ? "<a href=\"/delete/$post[post_id]\" class=\"btn btn-danger\">Delete</a>" : "" ?>
    <a href='/' class="btn btn-info">Back to materials</a>
</div>

<?= $this->endSection() ?>