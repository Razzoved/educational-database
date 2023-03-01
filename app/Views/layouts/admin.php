<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('header') ?>

    <?= $this->renderSection('header') ?>

    <link rel="stylesheet" href="<?= base_url('/css/collapsible.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/modal.css') ?>">

    <script>let lastPost = <?= json_encode($_POST ?? []) ?>;</script>
</head>

<body>
    <?= $this->include('admin/navigation_bar') ?>

    <div class="vh100">
        <?= $this->renderSection('content') ?>
    </div>

    <?= $this->renderSection('modals') ?>

    <?= $this->include('footer') ?>
    <?= $this->include('scripts') ?>

    <?= $this->renderSection('scripts') ?>
    <script type="text/javascript" src="<?= base_url('js/tabular.js') ?>"></script>
</body>

</html>
