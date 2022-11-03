<!-- MATERIAL DISPLAYED AS A CARD -->

<div class="card h-100 w-90 mb-4 text-bg-light" style="min-width:30vw">
    <div class="row g-0 m-1">

        <!-- draw image -->
        <div class="col-md-2 text-center" style="overflow:clip; max-width:80%; max-height:200px; object-fit:contain;">
            <img src=<?= $post->post_thumbnail ?> class="img-fluid rounded" alt="Missing image">
        </div>

        <!-- draw body of material -->
        <div class="col">

            <!-- draw title -->
            <div class="card-header text-bg-light">
                <h5 class="card-title"><?= $post->post_title ?></h5>
            </div>

            <div class="card-body">
                <!-- draw upload date -->
                <p class="card-text"><small class="text-muted"><?= $post->post_created_at ?></small></p>

                <!-- draw rating -->
                <p class="card-text"><small class="text-muted">Rating: <?= $post->post_rating ?></small></h5>

                <!-- draw truncated details -->
                <p class="card-text"><?= (strlen($post->post_content > 140)) ? substr($post->post_content, 0, 137) . '...' : $post->post_content ?></p>
            </div>
        </div>
    </div>

    <!-- draw footer -->
    <div class="card-footer">
        <div style="float:left; width:50%;">
            <p class="card-text"><small class="text-muted">Viewed: <?= $post->post_views ?>x</small></p>
        </div>
        <div style="float:right; width:50%;">
            <a href=<?= "/" . $post->post_id ?> class="btn btn-primary stretched-link" style="float:right">Details</a>
        </div>
    </div>

</div>
