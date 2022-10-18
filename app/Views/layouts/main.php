<!DOCTYPE html>
<html lang="en">

<head>
    <!-- metadata -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico"/>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <title><?= (isset($meta_title) ? $meta_title : 'Fallback title') ?></title>
</head>

<body>
<!-- bootstrap navigation bar -->
<?= $this->include('widgets/navigation_bar') ?>

<!-- Dynamic part of the layout -->
<div class="container" style="min-height: 100vh;">
    <?= $this->renderSection('content') ?>
</div>

<!-- Separate footer -->
<?= $this->include('widgets/footer') ?>

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>