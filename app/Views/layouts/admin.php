<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('header') ?>

    <?= $this->renderSection('header') ?>

    <link rel="stylesheet" href="<?= base_url('public/css/collapsible.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/css/admin.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/css/modal.css') ?>">

    <script>let lastSearch = <?= json_encode($_GET ?? []) ?>;</script>
</head>

<body>
    <?= $this->include('admin/navigation_bar') ?>

    <div class="container" style="min-height: 100vh">
        <?= $this->renderSection('content') ?>
    </div>

    <?= $this->renderSection('modals') ?>

    <?= $this->include('footer') ?>

    <?= $this->renderSection('scripts') ?>
    <script type="text/javascript" src="<?= base_url('public/js/tabular.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('public/js/error.js') ?>"></script>
</body>

</html>
