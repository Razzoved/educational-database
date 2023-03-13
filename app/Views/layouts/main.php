<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('header') ?>

    <link rel="stylesheet" href="<?= base_url('public/css/collapsible.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/css/public.css') ?>">

    <script>let lastSearch = <?= json_encode($_GET ?? []) ?>;</script>
</head>

<body>
    <?= $this->include('navigation_bar') ?>

    <div class="container" style="min-height: 100vh">
        <?= $this->renderSection('content') ?>
    </div>

    <?= $this->include('footer') ?>

    <?= $this->renderSection('scripts') ?>
    <script type="text/javascript" src="<?= base_url('public/js/tabular.js') ?>"></script>
</body>

</html>
