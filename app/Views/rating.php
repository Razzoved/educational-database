<?php
    /**
     * Renders a graphical rating display.
     *
     * @param \App\Entities\Material $material required
     */
?>
<div class="rating">
    <i class="fa-solid <?= $material->rating >= 0.8 ? 'active' : '' ?> fa-star"></i>
    <i class="fa-solid <?= $material->rating >= 1.8 ? 'active' : '' ?> fa-star"></i>
    <i class="fa-solid <?= $material->rating >= 2.8 ? 'active' : '' ?> fa-star"></i>
    <i class="fa-solid <?= $material->rating >= 3.8 ? 'active' : '' ?> fa-star"></i>
    <i class="fa-solid <?= $material->rating >= 4.8 ? 'active' : '' ?> fa-star"></i>
    <small><strong class="average"><?= $material->rating ?></strong></small>
    <small><u class="count"><?= $material->rating_count ?> ratings</u></small>
    <small>Viewed:<u class="views"><?= $material->views ?>x</u></small>
</div>