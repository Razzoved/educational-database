<a class="btn btn-primary" data-bs-toggle="offcanvas" href="#filterSidebar" role="button" aria-controls="filterSidebar">
    Filters
</a>

<?php
// TODO: REMOVE TEST DATA
$sample = [
    'Jan Martinek',
    'Hello world',
    'Some Author',
    'PHP Lover'
];
$authors = $sample;
$institutes = $sample;
?>

<!-- hiding will trigger filtering -->
<aside class="bd-sidebar">
    <div class="offcanvas offcanvas-start" tabindex="-1" id="filterSidebar" aria-labelledby="filterOffCanvasLabel">

        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="filterOffCanvasLabel">Filters</h5>
            <a class="btn btn-primary">Reset WIP</a>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <div>
                <h6>Authors</h6>
                <ul class="list-group">
                    <?php foreach($authors as $author) : ?>
                        <li class="list-group-item">
                        <input class="form-check-input me-2" type="checkbox" value="" aria-label="...">
                            <?= $author ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <h6>Institutes</h6>
                <ul class="list-group">
                    <?php foreach($sample as $s) : ?>
                        <li class="list-group-item">
                        <input class="form-check-input me-2" type="checkbox" value="" aria-label="...">
                            <?= $s ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <h6>Genre</h6>
                <ul class="list-group">
                    <?php foreach($sample as $s) : ?>
                        <li class="list-group-item">
                        <input class="form-check-input me-2" type="checkbox" value="" aria-label="...">
                            <?= $s ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <h6>Type</h6>
                <ul class="list-group">
                    <?php foreach($sample as $s) : ?>
                        <li class="list-group-item">
                        <input class="form-check-input me-2" type="checkbox" value="" aria-label="...">
                            <?= $author ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</aside>