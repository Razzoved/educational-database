<?php
    /**
     * Renders a collapsible list onto a page:
     * Data format: [priorities][properties] ... [properties]
     *
     * Expects:
     * @param Property $property property to display
     * @param string $type       buttons or checkboxes
     * @param int $maxIndex      maximum number of items before overflow
     */
    $childCount = sizeof($property->children);
    $maxIndex = $maxIndex ?? 4;
    $isFirstLevel = $isFirstLevel ?? false;
?>

<?php if ($childCount > 0) : ?>

<div class="collapsible collapsible--no-overflow<?= (!$isFirstLevel && $type !== 'button' ) ? ' collapsible--collapsed' : '' ?>">

    <div class="collapsible__header">

        <?php if ($type !== 'button') : ?>
            <input class="collapsible__toggle-group"
                type="checkbox"
                id="filter_<?= $property->id ?>"
                name="group"
                value="<?= $property->id ?>"
                title="Use all from group"
                onchange="toggleCollapsible(this, 'collapsible--selected')"
            />
        <?php else : ?>
            <button class="collapsible__header--search" type="button" onclick="searchCategory(this.closest('.collapsible'))">
                <input type="hidden"
                    id="filter_<?= $property->id ?>"
                    name="group"
                    value="<?= $property->id?>"
                    data-action="<?= url_to('Material::index') ?>"
                />
                <i class="fa fa-search" aria-hidden="true"></i>
            </button>
        <?php endif; ?>

        <button class="collapsible__toggle" type="button" onclick="toggleCollapsible(this)">
            <i class="fa-solid fa-caret-right"></i>
            <span><?= $property->value ?></span>
        </button>

    </div>

    <div class="collapsible__content">

        <ul class="collapsible__items">
            <?php $index = 0; ?>
            <?php foreach ($property->children as $item) : ?>
                <?= view('property/collapsible_item', [
                    'isOverflow' => $index++ >= $maxIndex,
                    'property' => $item,
                    'type' => $type,
                    'isFirstLevel' => false,
                ]) ?>
            <?php endforeach; ?>
        </ul>

        <?php if (sizeof($property->children) > $maxIndex) : ?>
            <button class="collapsible__toggle-overflow" type="button" onclick="toggleOverflow(this)">Show more</button>
        <?php endif; ?>

    </div>

    <script type="text/javascript">
        <?php include_once(FCPATH . 'js/collapsible.js') ?>
    </script>
</div>

<?php endif; ?>
