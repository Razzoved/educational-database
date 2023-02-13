<!-- MATERIAL DISPLAYED AS A CARD -->

<div class="card w-100 mb-4">
    <!-- draw header, contents -->
    <div class="row g-0 m-2">

        <!-- draw image -->
        <div class="col-sm-12 col-md-3 text-center">
            <img class="rounded me-1" src="<?= $material->getThumbnail()->getPath() ?>" style="width: 10rem; height: 10rem; object-fit: scale-down" alt="Missing image">
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
                    <?php
                        $content = strip_tags($material->content);
                        echo (strlen($content) > 300) ? substr($content, 0, 297) . '...' : `$content`;
                    ?>
                </p>
            </div>
        </div>

    </div>

    <!-- draw footer -->
    <div class="card-footer">
        <!-- draw rating, views, details -->
        <div class="row g-1" style="align-items: center; justify-content: center">
            <i class="col-auto <?= $material->rating >= 0.8 ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
            <i class="col-auto <?= $material->rating >= 1.8 ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
            <i class="col-auto <?= $material->rating >= 2.8 ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
            <i class="col-auto <?= $material->rating >= 3.8 ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
            <i class="col-auto <?= $material->rating >= 4.8 ? 'fa-solid' : 'fa-regular' ?> fa-star" style="margin-right: 0.2em"></i>
            <small class="col-auto" style="margin-right: 0.5em"><?= $material->rating?></small>
            <small class="col"><u><?= $material->rating_count ?> ratings</u></small>
            <small class="col-auto text-muted" style="margin-right: 1em">Viewed: <?= $material->views ?>x</small>
            <a class="col-auto btn btn-dark btn-sm stretched-link" href="<?= base_url('materials/' . $material->id) ?>">Details</a>
        </div>
    </div>

</div>
