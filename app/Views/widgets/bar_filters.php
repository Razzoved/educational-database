<a class="btn btn-primary my-2" data-bs-toggle="offcanvas" href="#filterSidebar" role="button" aria-controls="filterSidebar">
    Filters
</a>

<!-- hiding will trigger filtering -->
<aside class="bd-sidebar">
    <div class="offcanvas offcanvas-start" tabindex="-1" id="filterSidebar" aria-labelledby="filterOffCanvasLabel">

        <form method="post" action="">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="filterOffCanvasLabel">Filters</h5>
            <a class="btn btn-primary">Reset WIP</a>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <?php foreach($filters as $filter) : ?>
                <div>
                    <h6><?= $filter['property_type'] ?></h6>
                    <?= view_cell('\App\Libraries\Property::postFilter', ['filter' => $filter['property_type']]) ?>
                    <hr>
                </div>
            <?php endforeach; ?>
        </div>
        </form>

    </div>
</aside>