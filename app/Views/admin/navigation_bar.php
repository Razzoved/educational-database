<nav class="navbar navbar-expand navbar-dark bg-dark">
    <div class="navbar-nav" style="margin-left: 10px; font-size: larger; padding: 10px">
        <a class="nav-link active me-2" aria-current="page" href="<?= base_url('admin/dashboard') ?>")?>
            <i class="fa-solid fa-gauge"></i> Dashboard
        </a>
        <a class="nav-link active me-2" aria-current="page" href="<?= base_url('admin/materials') ?>")?>
            <i class="fa-solid fa-box-archive"></i> Materials
        </a>
        <a class="nav-link active me-2" aria-current="page" href="<?= base_url('admin/tags') ?>")?>
            <i class="fa-solid fa-tags"></i> Tags
        </a>
        <a class="nav-link active me-2" aria-current="page" href="<?= base_url('admin/files') ?>")?>
            <i class="fa-solid fa-file"></i> Unused files
        </a>
        <a class="nav-link active me-2" aria-current="page" href="<?= base_url('admin/users') ?>")?>
            <i class="fa-solid fa-user"></i> Users
        </a>
    </div>
    <div class="navbar-nav" style="margin-left: auto; margin-right: 10px; font-size: larger; padding: 10px">
        <a class="nav-link active" href="<?= base_url('/admin/logout') ?>">Logout <i class="fa-sharp fa-solid fa-right-from-bracket"></i></a>
    </div>
</nav>
