<div class="card mb-3" style="max-width: 540px;">
  <div class="row g-0">
    <div class="col-md-4">
      <img src=<?= $post['img'] ?> class="img-fluid rounded-start" alt="Missing image">
    </div>
    <div class="col-md-8">
      <div class="card-body">

        <!-- draw title and upload date --->
        <h5 class="card-title"><?= $post['title'] ?></h5>
        <p class="card-text"><small class="text-muted"><?= $post['uploadDate'] ?></small></p>
        
        <!-- draw rating --->
        <p class="card-text"><small class="text-muted">Rating: <?= $post['rating'] ?></small></h5>

        <!-- draw details --->
        <p class="card-text"><?= (strlen($post['text'] > 120)) ? substr($post['text'], 0, 117) . '...' : $post['text'] ?></p>
        <p class="card-text"><small class="text-muted">Last updated <?= $post['lastUpdate'] ?> ago.</small></p>
        <a href=<?= "/" . $post['id'] ?> class="btn btn-primary stretched-link">Details</a>
      </div>
    </div>
  </div>
</div>