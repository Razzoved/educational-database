<?php
    /**
     * Single material displayed as a responsive card.
     *
     * @param \App\Entities\Material $material required
     */
    $url = url_to('Material::get', $material->id);
    $content = strip_tags($material->content);
?>

<div class="card" onclick="window.location.href='<?= $url ?>'">
    <img class="card__thumbnail"
        src="<?= $material->getThumbnail()->getURL() ?>"
        alt="Missing image">
    <div class="card__body">
        <div class="card__header">
            <h2><?= $material->title ?></h5>
        </div>

        <div class="card__content">
            <p><small>Published: <?= $material->publishedToDate() ?></small></p>
            <p><?= (strlen($content) > 120) ? substr($content, 0, 117) . '...' : $content ?></p>
        </div>

        <div class="card__footer">
            <?= view('rating', ['material' => $material]) ?>
            <a href="<?= $url ?>">Details</a>
        </div>
    </div>
</div>
