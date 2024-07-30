<?php

/**
 * Navigation bar for the public domain.
 *
 * @var string $activePage last url segment of the current page
 */
$homeURL = model(\App\Models\ConfigModel::class)->find('home_url')->value ?? url_to('Material::index');

// resolve active page
$url = (string) current_url(true)->setQuery('');

$url_all = url_to('Material::index');
$url_top = url_to('Material::getByRating');
$url_mst = url_to('Material::getByViews');
$url_lgn = url_to('Authentication::index');

$all = $url === $url_all ? ' active' : '';
$top = $url === $url_top ? ' active' : '';
$mst = $url === $url_mst ? ' active' : '';
$lgn = $url === $url_lgn ? ' active' : '';
?>
<nav class="navbar">
    <div class="navbar__container">
        <img class="navbar__logo" id="link-home" src="<?= base_url('assets/enai-logo-transparent.png') ?>" alt="ENAI logo" href="<?= $homeURL ?>" onclick="window.location.href=this.getAttribute('href')">
        <ul class="navbar__list">
            <li class="navbar__item<?= $all ?>">
                <a class="navbar__button" href="<?= $url_all ?>">
                    All materials
                </a>
            </li>
            <li class="navbar__item<?= $top ?>">
                <a class="navbar__button" href="<?= $url_top ?>">
                    Top rated
                </a>
            </li>
            <li class="navbar__item<?= $mst ?>">
                <a class="navbar__button" href="<?= $url_mst ?>">
                    Most viewed
                </a>
            </li>
            <li class="navbar__item navbar__item--to-right navbar__item--switch<?= session('isLoggedIn') ? '' : ' navbar__item--auth' ?><?= $lgn ?>">
                <a class="navbar__button" href="<?= $url_lgn ?>">
                    <?= session('isLoggedIn') ? 'To administration' : 'Login' ?>
                </a>
            </li>
            <li class="navbar__item navbar__item--toggle">
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