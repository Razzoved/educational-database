<?php
    /**
     * Navigation bar for the public domain.
     *
     * @var string $homeURL optional parameter, changes the reference of brand image
     * @var string $activePage last url segment of the current page
     */
    $refUrl = defined('ROOTURL') ? ROOTURL : base_url();
    $activePage = isset($activePage) ? $activePage : '';
?>
<div class="navbar">
    <nav class="container">
        <img src="<?= base_url('public/assets/enai-logo-transparent.png') ?>"
                alt="ENAI logo"
                onclick="window.location.href='<?= $refUrl ?>'">
        <a <?= $activePage === '' ? 'class="active"' : '' ?> href="<?= base_url() ?>">All materials</a>
        <a <?= $activePage === 'top-rated' ? 'class="active"' : '' ?> href="<?= base_url('top-rated') ?>">Top rated</a>
        <a <?= $activePage === 'most-viewed' ? 'class="active"' : '' ?> href="<?= base_url('most-viewed') ?>">Most viewed</a>
        <a class="login" href="<?= base_url('admin') ?>"><?= session()->get('isLoggedIn') ? 'To administration' : 'Login' ?></a>
        <a class="icon" href="javascript:void(0);" onclick="navbar_toggle()">
            <i class="fa fa-bars"></i>
        </a>
    </nav>
</div>

<script type="text/javascript">
    function navbar_toggle() {
        document.querySelector(".navbar").classList.toggle('responsive');
    }
</script>
