<div class="card h-100 w-90 mb-4 text-bg-light" style="min-width:30vw;">
    <div class="row g-0 m-1">

        <!-- draw image -->
        <div class="col-md-2 text-center" style="overflow:clip; max-width:100%; max-height:200px; object-fit:contain;">
            <img src=<?= $post['img'] ?> class="img-fluid rounded" alt="Missing image">
        </div>

        <!-- draw body of material -->
        <div class="col">

            <!-- draw title -->
            <div class="card-header text-bg-light">
                <h5 class="card-title"><?= $post['title'] ?></h5>
            </div>

            <div class="card-body">
                <!-- draw upload date -->
                <p class="card-text"><small class="text-muted"><?= $post['uploadDate'] ?></small></p>

                <!-- draw rating -->
                <p class="card-text"><small class="text-muted">Rating: <?= $post['rating'] ?></small></h5>

                <!-- draw details -->
                <p class="card-text"><?= (strlen($post['text'] > 120)) ? substr($post['text'], 0, 117) . '...' : $post['text'] ?></p>
            </div>
        </div>
    </div>

    <!-- draw footer -->
    <div class="card-footer mh-10">
        <div style="float:left; width:50%;">
            <p class="card-text"><small class="text-muted">Viewed: <?= $post['views'] ?>x</small></p>
        </div>
        <div style="float:right; width:50%;">
            <a href=<?= "/" . $post['id'] ?> class="btn btn-primary stretched-link" style="float:right">Details</a>
        </div>
    </div>

</div>