<?php
    /**
     * Template for displaying material in a small square for viewing in matrix.
     *
     * @var materials array of material entity objects
     */
?>
<!-- group of MATERIALS shown as clickable links -->
<?php if (isset($materials) && $materials !== []) : ?>
<h2><?= $title ?></h5>
<ul class="relations">
    <?php foreach ($materials as $material) : ?>
    <li class="relations__item" onclick="window.location.href='<?= base_url('single/' . $material->id) ?>'" >
        <img class="relations__thumbnail"
            src="<?= $material->getThumbnail()->getURL() ?>"
            alt="Relation thumbnail">
        <p class="relations__title"><?= $material->title ?></p>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
