<?php
    /**
     * Navigation bar for the public domain.
     *
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
        <a <?= $activePage === 'dashboard' ? 'class="active"' : '' ?> href="<?= base_url('') ?>")?>
            <i class="fa-solid fa-gauge"></i> Public
        </a>
        <a <?= $activePage === 'materials' ? 'class="active"' : '' ?> href="<?= base_url('admin/materials') ?>")?>
            <i class="fa-solid fa-box-archive"></i> Materials
        </a>
        <a <?= $activePage === 'tags' ? 'class="active"' : '' ?> href="<?= base_url('admin/tags') ?>")?>
            <i class="fa-solid fa-tags"></i> Tags
        </a>
        <a <?= $activePage === 'files' ? 'class="active"' : '' ?> href="<?= base_url('admin/files') ?>")?>
            <i class="fa-solid fa-file"></i> Unused files
        </a>
        <a <?= $activePage === 'users' ? 'class="active"' : '' ?> href="<?= base_url('admin/users') ?>")?>
            <i class="fa-solid fa-user"></i> Users
        </a>
        <a class="login" href="<?= base_url('/admin/logout') ?>">
            Logout <i class="fa-sharp fa-solid fa-right-from-bracket"></i>
        </a>
        <a class="icon" href="javascript:void(0);" onclick="navbar_toggle()">
            <i class="fa fa-bars"></i>
        </a>
    </nav>
</div>

<script type="text/javascript">
    function navbar_toggle() {
        var x = document.querySelector(".navbar");
        if (x.className === "navbar") {
            x.className += " responsive";
        } else {
            x.className = "navbar";
        }
    }
</script>
