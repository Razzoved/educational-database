<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Post container -->
<div class="container" style="margin-bottom: 5vh;">
    <!-- Header with image and tags -->
    <div>
        <!-- Thumbnail -->
        <div>
            <img src=<?= $post->post_thumbnail ?> alt="thumnail">
        </div>
        <!-- Title and tags -->
        <div>
            <!-- Title -->
            <h1><?= $post->post_title ?></h1>
            <!-- Tags -->
            <div>
                <pre><?= print_r($post->getGroupedProperties()) ?></pre>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div>
    </div>

    <!-- Materials -->
    <div>
    </div>

    <!-- Actions -->
    <div>
        <a href="/edit/$post->post_id" class="btn btn-primary">Edit</a>
        <a href="/delete/$post->post_id" class="btn btn-danger">Delete</a>
        <a href='/' class="btn btn-info">Back to materials</a>
    </div>
</div>

<?= $this->endSection() ?>
