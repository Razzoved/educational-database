<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('header') ?>

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
</body>

</html>
