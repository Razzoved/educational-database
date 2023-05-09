<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('header') ?>
    <link rel="stylesheet" href="<?= base_url('public/css/public.css') ?>">
    <?= $this->renderSection('header') ?>
</head>

<body>
    <?= $this->include('navigation_bar') ?>

    <?php
        $pageClass = array('page');
        if (
            (isset($filters) && !empty($filters)) ||
            (isset($hasSidebar) && $hasSidebar === true)
        ) {
            $pageClass[] = 'page--has-sidebar';
            $hasSidebar = true;
        } else {
            $hasSidebar = false;
        }
        $pageClass[] = 'page';
        $pageClass = implode(' ', array_reverse($pageClass));
    ?>

    <main id="top" class="container">
        <div class="<?= $pageClass ?>">
            <?php if (isset($title)) echo ('<h1 class="page__title">' . $title . '</h1>') ?>

            <?php if ($hasSidebar) : ?>
                <div class="page__sidebar">
                    <?= $this->renderSection('sidebar') ?>
                </div>
            <?php endif; ?>

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
