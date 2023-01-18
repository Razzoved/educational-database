<!-- Offcanvas that contains a list of collapsible lists, to use it,
     create a button that will reference its #id (and aria-control),
     and wrap them both inside a <form method=?? action=??></form> -->
<aside class="bd-sidebar">
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasSidebarLabel">Filters</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <button class="btn btn-success w-100 mb-2">Apply</button>
            <button type="button" class="btn btn-dark w-100 mb-2" onclick="{ document.querySelectorAll('.clps input[type=checkbox]').forEach(e => e.checked=false); document.querySelectorAll('input[name=search]').forEach(e => e.value=''); }">Reset filters</button>
            <ul class="list-unstyled ps-0">
                <?php foreach ($properties as $tag => $values) : ?>
                    <?= view('widgets/collapsible_list', ['tag' => $tag, 'values' => $values, 'type' => 'checkbox']) ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</aside>
