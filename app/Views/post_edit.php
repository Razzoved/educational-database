<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Menu bar -->
<div class="container" style="margin-bottom: 5vh;">
    <h1><?= $title ?></h1>
</div>

<!-- Presents all materials in a nicer html --->
<div class="row container">
    <div class="col">
        <form method="post" action="/edit/<?= $post->post_id ?>">
            <div class="form-group m-2">
                <label for="">Title</label>
                <input id="" class="form-control" type="text" name="post_title" value="<?= $post->post_title ?>">
            </div>
            <div class="form-group m-2">
                <label for="">Type</label>
                <input id="" class="form-control" type="text" name="post_type" value="<?= $post->post_type ?>">
            </div>
            <div class="form-group m-2">
                <label for="">Description</label>
                <textarea id="" class="form-control" name="post_content" rows=3><?= $post->post_content ?></textarea>
            </div>
            <div class="form-group m-2">
                <button class="btn btn-success btn-sm">Edit</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
