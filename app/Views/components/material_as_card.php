<?php
    /**
     * Single material displayed as a responsive card.
     *
     * @param \App\Entities\Material $material required
     */
    $url = base_url('single/' . $material->id);
    $content = strip_tags($material->content);
?>

<div class="card" onclick="window.location.href='<?= $url ?>'">
    <div class="card__thumbnail">
        <img src="<?= $material->getThumbnail()->getURL() ?>" alt="Missing image">
    </div>
    <div class="card__body">
        <div class="card__header">
            <h2><?= strlen($material->title) > 50 ? substr($material->title, 0, 50) . '...' : $material->title ?></h5>
        </div>

        <div class="card__content">
            <p><small>Upload date: <?= $material->createdToDate() ?></small></p>
            <p><?= (strlen($content) > 120) ? substr($content, 0, 117) . '...' : $content ?></p>
        </div>

        <div class="card__footer">
            <?= view('rating', ['material' => $material]) ?>
            <a href="<?= $url ?>">Details</a>
        </div>
    </div>
</div>
