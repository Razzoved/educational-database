<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('header') ?>
    <link rel="stylesheet" href="<?= base_url('public/css/public.css') ?>">
</head>

<body>
    <?= $this->include('navigation_bar') ?>

    <main id="top" class="container">
        <div class="page">
            <?php if (isset($title)) echo ('<h1 class="page__title">' . $title . '</h1>') ?>
            <?= $this->renderSection('sidebar') ?>
            <div class="page__content">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </main>

    <?= $this->include('tooltip') ?>
    <?= $this->include('go_up') ?>
    <?= $this->include('footer') ?>

    <?= $this->renderSection('scripts') ?>
    <script type="text/javascript" src="<?= base_url('public/js/tabular.js') ?>"></script>
</body>

</html>
