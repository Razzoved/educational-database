<form method="post" action="">

<div class="row form-group">
    <div class="col" style="margin-right:-1em">
        <input class="form-control" name="search" value="" placeholder="Search"/>
    </div>
    <div class="col">
        <button class="btn btn-success">Search</button>
        <a class="btn btn-primary" data-bs-toggle="offcanvas" href="#filterSidebar" aria-controls="filterSidebar">Filters</a>
    </div>
</div>

<aside class="bd-sidebar">
    <div class="offcanvas offcanvas-start" tabindex="-1" id="filterSidebar" aria-labelledby="filterOffCanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="filterOffCanvasLabel">Filters</h5>
            <button class="btn btn-success">Search</button>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <?= view_cell('\App\Libraries\Property::postFilters', $filters) ?>
        </div>
    </div>
</aside>

</form>
