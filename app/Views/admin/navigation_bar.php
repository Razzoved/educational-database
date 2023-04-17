<?php
    /**
     * Navigation bar for the public domain.
     *
     * @var string $activePage last url segment of the current page
     */
    $refUrl = defined('ROOTURL') ? ROOTURL : url_to('Material::index');
    $activePage = isset($activePage) ? $activePage : '';

    $dash = $activePage === 'dashboard' ? ' active' : '';
    $mats = $activePage === 'materials' ? ' active' : '';
    $prop = $activePage === 'tags' ? ' active' : '';
    $file = $activePage === 'files' ? ' active' : '';
    $user = $activePage === 'users' ? ' active' : '';
?>
<nav class="navbar">
    <div class="navbar__container">
        <img class="navbar__logo"
            src="<?= base_url('public/assets/enai-logo-transparent.png') ?>"
            alt="ENAI logo"
            onclick="window.location.href='<?= $refUrl ?>'">
        <ul class="navbar__list">
            <li class="navbar__item">
                <a class="navbar__button<?= $dash ?>" href="<?= url_to('Admin\Dashboard::index') ?>")?>
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
            </li>
            <li class="navbar__item">
                <a class="navbar__button<?= $mats ?>" href="<?= url_to('Admin\Material::index') ?>")?>
                    <i class="fa-solid fa-box-archive"></i> Materials
                </a>
            </li>
            <li class="navbar__item">
                <a class="navbar__button<?= $prop ?>" href="<?= url_to('Admin\Property::index') ?>")?>
                    <i class="fa-solid fa-tags"></i> Tags
                </a>
            </li>
            <li class="navbar__item">
                <a class="navbar__button<?= $file ?>" href="<?= url_to('Admin\Resource::index') ?>")?>
                    <i class="fa-solid fa-file"></i> Files
                </a>
            </li>
            <li class="navbar__item">
                <a class="navbar__button<?= $user ?>" href="<?= url_to('Admin\User::index') ?>")?>
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
