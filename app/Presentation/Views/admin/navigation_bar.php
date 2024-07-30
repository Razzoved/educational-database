<?php

/**
 * Navigation bar for the admin domain.
 */

// path to root website
$homeURL = model(\App\Models\ConfigModel::class)->find('home_url')->value ?? url_to('Admin\Dashboard::index');

// resolve active page
$url = (string) current_url(true)->setQuery('');
$dash = $url === url_to('Admin\Dashboard::index') ? ' active' : '';
$mats = $url === url_to('Admin\Material::index')  ? ' active' : '';
$prop = $url === url_to('Admin\Property::index')  ? ' active' : '';
$file = $url === url_to('Admin\Resource::index')  ? ' active' : '';
$user = $url === url_to('Admin\User::index')      ? ' active' : '';
?>
<nav class="navbar">
    <div class="navbar__container">
        <img class="navbar__logo" id="link-home" src="<?= base_url('assets/enai-logo-transparent.png') ?>" alt="ENAI logo" href="<?= $homeURL ?>" onclick="window.location.href=this.getAttribute('href')">
        <ul class="navbar__list">
            <li class="navbar__item<?= $dash ?>">
                <a class="navbar__button" href="<?= url_to('Admin\Dashboard::index') ?>" )?>
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
            </li>
            <li class="navbar__item<?= $mats ?>">
                <a class="navbar__button" href="<?= url_to('Admin\Material::index') ?>" )?>
                    <i class="fa-solid fa-box-archive"></i> Materials
                </a>
            </li>
            <li class="navbar__item<?= $prop ?>">
                <a class="navbar__button" href="<?= url_to('Admin\Property::index') ?>" )?>
                    <i class="fa-solid fa-tags"></i> Tags
                </a>
            </li>
            <li class="navbar__item<?= $file ?>">
                <a class="navbar__button" href="<?= url_to('Admin\Resource::index') ?>" )?>
                    <i class="fa-solid fa-file"></i> Files
                </a>
            </li>
            <li class="navbar__item<?= $user ?>">
                <a class="navbar__button" href="<?= url_to('Admin\User::index') ?>" )?>
                    <i class="fa-solid fa-user"></i> Users
                </a>
            </li>
            <li class="navbar__item navbar__item--to-right navbar__item--switch">
                <a class="navbar__button" href="<?= url_to('Material::index') ?>">To public</a>
            </li>
            <li class="navbar__item navbar__item--switch navbar__item--auth">
                <a class="navbar__button" href="<?= url_to('Authentication::logout') ?>">
                    <i class="fa-sharp fa-solid fa-right-from-bracket"></i> Logout
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