<?php
    /* TEMPLATE: shows a single property as a row in administration panel. */

    /**
     * @var int $id id of the property
     */
    $id = $id ?? '@id@';

    /**
     * @var string $title Value of the property
     */
    $title = $title ?? '@title@';

    /**
     * @var string $tag Value of property's parent (must be "" if you want it to be empty)
     */
    $tag = $tag ?? '@tag@';

    /**
     * @var int $usage Number of times the property has been used
     */
    $usage = $usage ?? '@usage@';

    /**
     * @var string $description Description of the property.
     *
     * Must be "" if you want it to be empty.
     * If handled in 'js' you may want to remove 'tooltipText' element on empty.
     */
    $description = $description ?? '@description@';
?>

<div id="<?= $id ?>" class="tooltip">
    <div class="item">
        <div class="item__header">
            <h2 class="item__title" data-value="value"><?= $title ?></h2>
            <div class="item__controls">
                <button class="item__edit" type="button" onclick="propertyOpen(<?= $id ?>)">
                    Edit
                </button>
                <button class="item__delete" type="button" onclick="deleteOpen(<?= $id ?>)">
                    &#10005;
                </button>
            </div>
        </div>
        <div class="item__row">
            <p class="item__text" data-value="tag">
                <?= $tag ?>
            </p>
            <p class="item__text" data-value="id">
                <small>ID:</small><br><?= $id ?>
            </p>
            <p class="item__text" data-value="usage">
                <small>Usage:</small><br><?= $usage ?>
            </p>
        </div>
    </div>
    <?php if ($description !== '') : ?>
        <span class="tooltip__text"><?= $description ?></span>
    <?php endif; ?>
</div>
