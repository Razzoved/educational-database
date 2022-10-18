<div class="container">
    <nav class="navbar navbar-expand-lg bg-white">
        <div class="container-fluid">
            <a class="navbar-brand" href=<?= (isset($homeURL) ? $homeURL : '/')?>><img src="/assets/enai-logo_horizontal.png" width="auto" height="80px" alt="ENAI logo"></a>
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