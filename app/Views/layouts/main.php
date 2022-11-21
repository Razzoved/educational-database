<!DOCTYPE html>
<html lang="en">

<!-- takes meta_title -->
<?= $this->include('layouts/head') ?>

<body>
    <!-- bootstrap navigation bar -->
    <?= $this->include('widgets/navigation_bar') ?>

    <!-- Dynamic part of the layout -->
    <div class="container vh100">
        <?= $this->renderSection('content') ?>
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
