<div class="row">
    <?= $this->renderSection('visible') ?>
</div>

<aside class="bd-sidebar">
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
        <div class="offcanvas-header">
            <?= $this->renderSection('offcanvas_header') ?>
        </div>
        <div class="offcanvas-body">
            <?= $this->renderSection('offcanvas_body') ?>
        </div>
    </div>
</aside>
