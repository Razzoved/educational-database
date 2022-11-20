<!-- MATERIAL DISPLAYED AS A CARD -->

<div class="card w-100 mb-4">
    <!-- draw header, contents -->
    <div class="row g-0 m-2">

        <!-- draw image -->
        <div class="col-sm-12 col-md-4 col-lg-2 text-center">
            <img src=<?= $material->getThumbnail() ?> class="img-fluid rounded" alt="Missing image">
        </div>

        <!-- draw body of material -->
        <div class="col mt-2 mt-md-0 ms-0 ms-md-2 bg-white">
            <!-- header -->
            <div class="card-header bg-light text-bg-light rounded pt-2 pb-1 pe-0 pw-0">
                <h5 class="card-title"><?= $material->title ?></h5>
            </div>
            <!-- body -->
            <div class="card-body text-bg-white rounded">
                <p class="card-text">
                    <small class="text-muted">Upload date: <?= $material->createdToDate() ?></small>
                </p>
                <p class="card-text">
                    <?= (strlen($material->content) > 280) ? substr($material->content, 0, 277) . '...' : $material->content ?>
                </p>
            </div>
        </div>

    </div>

    <!-- draw footer -->
    <div class="card-footer">
        <!-- draw rating, views, details -->
        <div class="row g-1" style="align-items: center; justify-content: center">
            <i class="col-auto bi bi-star-fill"></i>
            <i class="col-auto bi bi-star-fill"></i>
            <i class="col-auto bi bi-star-fill"></i>
            <i class="col-auto bi bi-star"></i>
            <i class="col-auto bi bi-star" style="margin-right: 0.2em"></i>
            <small class="col-auto" style="margin-right: 0.5em"><?= $material->rating?></small>
            <small class="col"><u><?= $material->rating?> ratings</u></small> <!-- TODO: implement this table and query -->
            <small class="col-auto text-muted" style="margin-right: 1em">Viewed: <?= $material->views ?>x</small>
            <a class="col-auto btn btn-dark btn-sm stretched-link" href="/materials/<?= $material->id?>">Details</a>
        </div>
    </div>

</div>
