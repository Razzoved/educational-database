<!DOCTYPE html>
<html lang="en">

<?= $this->include('layouts/head') ?>

<body class="container-fluid g-0">

    <?= $this->include('admin/user_bar') ?>

    <!-- main body -->
    <div class="row">
        <?php $sideBar = 280; $padding = 20 ?>
        <!-- admin menu sidebar -->
        <div class="col-auto bg-info d-none d-md-block" style="height: 100%; min-height: 100vh; width: <?= $sideBar ?>px">
            <?= $this->include('admin/sidebar') ?>
        </div>

        <div class="col-auto bg-white d-none d-md-block" style="width: <? $padding ?>px"></div>

        <!-- Dynamic part of the layout -->
        <div class="col bg-primary" style="height: 100%; min-height: 100vh">
            <?= $this->renderSection('content') ?>
        </div>

        <div class="col-auto bg-white d-none d-xxl-block" style="width: <?= $sideBar + $padding ?>px"></div>
    </div>

    <!-- Separate footer -->
    <?= $this->include('layouts/footer') ?>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
            crossorigin="anonymous">
    </script>
</body>
</html>
