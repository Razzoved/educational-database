<!DOCTYPE html>
<html lang="en">

<?= $this->include('layouts/head') ?>

<body class="container-fluid g-0">

    <?= $this->include('admin/user_bar') ?>

    <div class="row g-0">
        <div class="col-auto side_dr">
            <?= $this->include('admin/sidebar') ?>
        </div>
        <div class="col vh100">
            <div class="container bg-primary vh100">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
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
