<?php
    /**
     * Renders a graphical rating display.
     *
     * @param \App\Entities\Material $material required
     */
?>
<div class="rating">
    <div class="rating__stars">
        <i class="rating__star fa-solid <?= $material->rating >= 0.8 ? 'active' : '' ?> fa-star"></i>
        <i class="rating__star fa-solid <?= $material->rating >= 1.8 ? 'active' : '' ?> fa-star"></i>
        <i class="rating__star fa-solid <?= $material->rating >= 2.8 ? 'active' : '' ?> fa-star"></i>
        <i class="rating__star fa-solid <?= $material->rating >= 3.8 ? 'active' : '' ?> fa-star"></i>
        <i class="rating__star fa-solid <?= $material->rating >= 4.8 ? 'active' : '' ?> fa-star"></i>
    </div>
    <small class="rating__count" style="min-width: 2rem">Rated:&nbsp;<u class="rating__count-value"><?= $material->rating_count ?>x</u></small>
    <small class="rating__views" style="min-width: 2rem">Viewed:&nbsp;<u class="rating__views-value"><?= $material->views ?>x</u></small>
</div>
