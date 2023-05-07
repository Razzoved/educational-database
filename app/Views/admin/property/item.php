<?php
    /* TEMPLATE: shows a single property as a row in administration panel. */

    /**
     * @var int $id id of the property
     */
    $id = $id ?? '@id@';

    /**
     * @var string $value Value of the property
     */
    $value = $value ?? '@value@';

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
     */
    $description = isset($description)
        ? ($description === "" ? "" : "data-tooltip='{$description}'")
        : '';
?>

<div id="<?= $id ?>" <?= $description ?> class="item">
    <div class="item__header">
        <h2 class="item__title" data-value="value"><?= $value ?></h2>
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
        <p class="item__text" data-value="id">
            <small>ID:</small><br><?= $id ?>
        </p>
        <p class="item__text" data-value="tag">
            <?= $tag ?>
        </p>
        <p class="item__text" data-value="usage">
            <small>Usage:</small><br><?= $usage ?>
        </p>
    </div>
</div>
