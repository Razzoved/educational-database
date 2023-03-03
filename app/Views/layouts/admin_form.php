<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('header') ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- custom css -->
    <link rel="stylesheet" href="<?= base_url('css/edit.css') ?>">

    <?= $this->renderSection('header') ?>

</head>

<body>
    <?= $this->include('admin/navigation_bar') ?>

    <div class="container vh100">
        <?= $this->renderSection('content') ?>
    </div>

    <?= $this->include('footer') ?>
    <?= $this->include('scripts') ?>
    <?= $this->renderSection('scripts') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
