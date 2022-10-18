<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Menu bar -->
<div class="container" style="margin-bottom: 5vh;">
    <h1><?= $title ?></h1>
</div>

<!-- Presents all materials in a nicer html --->
<div class="row container">
    <div class="col">
        <form method="post" action="/new">
            <div class="form-group m-2">
                <label for="">Title</label>
                <input id="" class="form-control" type="text" name="post_title">
            </div>
            <div class="form-group m-2">
                <label for="">Type</label>
                <input id="" class="form-control" type="text" name="post_type">
            </div>
            <div class="form-group m-2">
                <label for="">Description</label>
                <textarea id="" class="form-control" name="post_content" rows=3></textarea>
            </div>
            <div class="form-group m-2">
                <button class="btn btn-success btn-sm">Create</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>