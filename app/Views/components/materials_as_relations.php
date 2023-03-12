<?php
    /**
     * Template for displaying material in a small square for viewing in matrix.
     *
     * @var materials array of material entity objects
     */
?>
<!-- group of MATERIALS shown as clickable links -->
<?= ($materials == []) ? "" : "<hr><h2>$title</h5>" ?>
<div class="relations">
    <?php foreach ($materials as $material) : ?>
    <div class="relation" onclick="window.location.href='<?= base_url('single/' . $material->id) ?>'" >
        <img class="relation-thumbnail" src="<?= $material->getThumbnail()->getURL() ?>" alt="relation thumbnail">
        <p class="relation-title"><?= $material->title ?></p>
    </div>
    <?php endforeach; ?>
</div>
