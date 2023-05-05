<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('header') ?>

    <!-- custom css -->
    <link rel="stylesheet" href="<?= base_url('public/css/admin.css') ?>">

    <?= $this->renderSection('header') ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>

<body>
    <?= $this->include('admin/navigation_bar') ?>

    <main id="top" class="container">
        <?= $this->renderSection('content') ?>
        <?= $this->renderSection('modals') ?>
    </main>

    <?= $this->include('tooltip') ?>
    <?= $this->include('go_up') ?>
    <?= $this->include('footer') ?>

    <?= $this->renderSection('scripts') ?>
    <script type="text/javascript" src="<?= base_url('public/js/tabular.js') ?>"></script>
</body>

</html>
