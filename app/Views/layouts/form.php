<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('header') ?>
    <?= $this->renderSection('header') ?>

    <!-- custom css -->
    <link rel="stylesheet" href="<?= base_url('public/css/form.css') ?>">
</head>

<body>
    <?php if (session('isLoggedIn')) {
        echo $this->include('admin/navigation_bar');
    } else {
        echo view('navigation_bar', ['activePage' => 'login']);
    }
    ?>

    <main class="container">
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('footer') ?>
    <?= $this->renderSection('scripts') ?>
</body>

</html>