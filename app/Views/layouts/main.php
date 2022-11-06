<!DOCTYPE html>
<html lang="en">

<head>
    <!-- metadata -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico"/>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('/css/collapsible.css') ?>" type="text/css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <!-- Bootsrap icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <title><?= (isset($meta_title) ? $meta_title : 'Fallback title') ?></title>
</head>

<body>
<!-- bootstrap navigation bar -->
<?= $this->include('widgets/navigation_bar') ?>

<!-- Dynamic part of the layout -->
<div class="container" style="height: 100%; min-height: 100vh">
    <?= $this->renderSection('content') ?>
</div>

<!-- Separate footer -->
<?= $this->include('layouts/footer') ?>

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>
