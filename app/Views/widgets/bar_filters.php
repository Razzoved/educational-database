<a class="btn btn-primary my-2" data-bs-toggle="offcanvas" href="#filterSidebar" role="button" aria-controls="filterSidebar">
    Filters
</a>

<aside class="bd-sidebar">
    <div class="offcanvas offcanvas-start" tabindex="-1" id="filterSidebar" aria-labelledby="filterOffCanvasLabel">

        <form method="post" action="">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="filterOffCanvasLabel">Filters</h5>
                <button class="btn btn-success">Search</button>
                <a type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></a>
            </div>

            <div class="offcanvas-body">
                <?php foreach($filters as $filter) : ?>
                    <ul>
                        <h6><?= $filter['property_type'] ?></h6>
                        <?= view_cell('\App\Libraries\Property::postFilter', ['filter' => $filter['property_type']]) ?>
                        <hr>
                    </ul>
                <?php endforeach; ?>
            </div>
        </form>

    </div>
</aside>