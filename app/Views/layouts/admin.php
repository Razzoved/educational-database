<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('header') ?>

    <!-- custom css -->
    <link rel="stylesheet" href="<?= base_url('css/admin.css') ?>">

    <?= $this->renderSection('header') ?>
</head>

<body>
    <?= $this->include('admin/navigation_bar') ?>

    <main id="top" class="container">
        <?php
        $pageClass = $pageClass ?? [];
        if (!is_array($pageClass)) {
            $pageClass = array($pageClass);
        }
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

        <div class="<?= $pageClass ?>">
            <?php if (isset($title)) : ?>
                <h1 class="page__title"><?= $title ?></h1>
            <?php endif; ?>

            <?php if ($hasSidebar) : ?>
                <div class="page__sidebar">
                    <?= $this->renderSection('sidebar') ?>
                </div>
            <?php endif; ?>

            <div class="page__content">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
        <?= $this->renderSection('modals') ?>
    </main>

    <?= $this->include('tooltip') ?>
    <?= $this->include('go_up') ?>
    <?= $this->include('footer') ?>

    <?= $this->renderSection('scripts') ?>
</body>

</html>