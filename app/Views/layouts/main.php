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

<!-- bootstrap navigation bar --->
<div class="container">
    <nav class="navbar navbar-nav-fill-lg bg-white">
        <div class="container-fluid">
            <a class="navbar-brand" href=<?= (isset($homeURL) ? $homeURL : '/')?>><img src="assets/enai-logo_horizontal.png" width="auto" height="80px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" href=<?= (isset($homeURL) ? $homeURL : '/')?>>Home</a>
                    <a class="nav-link active" aria-current="page" href="/all">All materials</a>
                </div>
            </div>
        </div>
    </nav>
</div>
<hr>

<!-- Dynamic part of the layout --->
<div class="container">
    <?= $this->renderSection('content') ?>
</div>

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>