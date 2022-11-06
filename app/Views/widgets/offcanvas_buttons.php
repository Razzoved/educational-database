<!-- Offcanvas that contains a list of collapsible lists of buttons. To use
     it, create a button that will reference its #id (and aria-control). -->
<aside class="bd-sidebar">
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasSidebarLabel">Tags</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="list-unstyled ps-0">
                <?php foreach ($properties as $tag => $values) : ?>
                    <?= view('widgets/collapsible_list', ['tag' => $tag, 'values' => $values, 'type' => 'button']) ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</aside>
