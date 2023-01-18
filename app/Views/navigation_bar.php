<div class="border-bottom mb-3">
    <nav class="container navbar navbar-expand-lg">
        <div class="container-fluid">

            <?php $refUrl = isset($homeURL) ? $homeURL : base_url() ?>

            <a class="navbar-brand" href="<?= $refUrl ?>"><img src="<?= base_url('assets/enai-logo_horizontal.png') ?>" width="auto" height="80px" alt="ENAI logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" href="<?= $refUrl ?>">Home</a>
                    <a class="nav-link active" aria-current="page" href="<?= base_url() ?>">All materials</a>
                </div>
                <div class="navbar-nav" style="margin-left: auto">
                    <a class="nav-link active" aria-current="page" href="<?= base_url('login') ?>">Login</a>
                </div>
            </div>

        </div>
    </nav>
</div>
