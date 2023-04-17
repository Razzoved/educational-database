<?php
    /**
     * Navigation bar for the public domain.
     *
     * @var string $homeURL optional parameter, changes the reference of brand image
     * @var string $activePage last url segment of the current page
     */
    $refUrl = defined('ROOTURL') ? ROOTURL : url_to('Material::index');

    $activePage = isset($activePage) ? $activePage : '';
    $default = $activePage === '' ? ' active' : '';
    $topRated = $activePage === 'top-rated' ? ' active' : '';
    $mostViewed = $activePage === 'most-viewed' ? ' active' : '';
    $login = $activePage === 'login' ? ' active' : '';
?>
<nav class="navbar">
    <div class="navbar__container">
        <img class="navbar__logo"
            src="<?= base_url('public/assets/enai-logo-transparent.png') ?>"
            alt="ENAI logo"
            onclick="window.location.href='<?= $refUrl ?>'">
        <ul class="navbar__list">
            <li class="navbar__item<?= $default ?>">
                <a class="navbar__button" href="<?= url_to('Material::index') ?>">
                    All materials
                </a>
            </li>
            <li class="navbar__item<?= $topRated ?>">
                <a class="navbar__button" href="<?= url_to('MaterialTopRated::index') ?>">
                    Top rated
                </a>
            </li>
            <li class="navbar__item<?= $mostViewed ?>">
                <a class="navbar__button" href="<?= url_to('MaterialMostViewed::index') ?>">
                    Most viewed
                </a>
            </li>
            <li class="navbar__item navbar__item--to-right navbar__item--switch<?= session('isLoggedIn') ? '' : ' navbar__item--auth'?><?= $login ?>">
                <a class="navbar__button" href="<?= url_to('Authentication::login') ?>">
                    <?= session('isLoggedIn') ? 'To administration' : 'Login' ?>
                </a>
            </li>
            <li class="navbar__item">
                <a class="navbar__toggle" href="javascript:void(0);" onclick="navbar_toggle()">
                    <i class="fa fa-bars"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>

<script type="text/javascript">
    function navbar_toggle() {
        document.querySelector(".navbar").classList.toggle('navbar--responsive');
    }
</script>
