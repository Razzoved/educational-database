<?php
    /**
     * Navigation bar for the public domain.
     *
     * @var string $activePage last url segment of the current page
     */
    $refUrl = defined('ROOTURL') ? ROOTURL : base_url();
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
                <a class="navbar__button<?= $dash ?>" href="<?= base_url('') ?>")?>
                    <!-- <i class="fa-solid fa-gauge"></i> --> Public
                </a>
            </li>
            <li class="navbar__item">
                <a class="navbar__button<?= $mats ?>" href="<?= base_url('admin/materials') ?>")?>
                    <i class="fa-solid fa-box-archive"></i> Materials
                </a>
            </li>
            <li class="navbar__item">
                <a class="navbar__button<?= $prop ?>" href="<?= base_url('admin/tags') ?>")?>
                    <i class="fa-solid fa-tags"></i> Tags
                </a>
            </li>
            <li class="navbar__item">
                <a class="navbar__button<?= $file ?>" href="<?= base_url('admin/files') ?>")?>
                    <i class="fa-solid fa-file"></i> Files
                </a>
            </li>
            <li class="navbar__item">
                <a class="navbar__button<?= $user ?>" href="<?= base_url('admin/users') ?>")?>
                    <i class="fa-solid fa-user"></i> Users
                </a>
            </li>
            <li class="navbar__item navbar__item--to-right">
                <a class="navbar__button" href="<?= base_url('/admin/logout') ?>">
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
