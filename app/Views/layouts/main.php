<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('header') ?>

    <link rel="stylesheet" href="<?= base_url('public/css/public.css') ?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>

<body>
    <?= $this->include('navigation_bar') ?>

    <main class="container">
        <div class="page">
            <?php if (isset($title)) echo ('<h1 class="page__title">' . $title . '</h1>') ?>
            <div class="page__body">
                <!-- render sidebar if present -->
                <?php $sidebar = $this->renderSection('sidebar') ?? false ?>
                <?php if ($sidebar) echo ('<div class="page__sidebar">' . $sidebar . '</div>') ?>
                <!-- render content if present -->
                <?php $content = $this->renderSection('content') ?? false ?>
                <?php if ($content) echo ('<div class="page__content">' . $content . '</div>') ?>
            </div>
        </div>
    </main>

    <?= $this->include('footer') ?>

    <?= $this->renderSection('scripts') ?>
    <script type="text/javascript" src="<?= base_url('public/js/tabular.js') ?>"></script>
</body>

</html>
