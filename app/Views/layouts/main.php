<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('header') ?>

    <link rel="stylesheet" href="<?= base_url('/css/collapsible.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/sidebar.css') ?>">

    <script>let lastPost = <?= json_encode($_POST ?? []) ?>;</script>
</head>

<body>
    <?= $this->include('navigation_bar') ?>

    <div class="container" style="min-height: 100vh">
        <?= $this->renderSection('content') ?>
    </div>

    <?= $this->include('footer') ?>
    <?= $this->include('scripts') ?>
    <script type="text/javascript" src="<?= base_url('js/tabular.js') ?>"></script>
</body>

</html>
