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
    $isFirstLevel = $property->tag == 0;
?>

<?php if ($childCount > 0) : ?>

<div class="collapsible collapsible--no-overflow<?= !$isFirstLevel ? ' collapsible--collapsed' : '' ?>">

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
        <?php endif; ?>

        <button class="collapsible__toggle" type="button" onclick="toggleCollapsible(this)">
            <i class="fa-solid fa-caret-right"></i>
            <span><?= $property->value ?></span>
        </button>

    </div>

    <div class="collapsible__content">

        <ul class="collapsible__items">
            <?php
                $index = 0;
                $children = $property->children;
                if ($type === 'button') {
                    array_unshift($children, new App\Entities\Property([
                        'id' => $property->id,
                        'tag' => $property->tag,
                        'value' => $property->value,
                    ]));
                }
            ?>

            <?php foreach ($children as $item) : ?>
                <?= view('property/collapsible_item', [
                    'isOverflow' => $index++ >= $maxIndex,
                    'property' => $item,
                    'type' => $type
                ]) ?>
            <?php endforeach; ?>
        </ul>

        <?php if (sizeof($children) > $maxIndex) : ?>
            <button class="collapsible__toggle-overflow" type="button" onclick="toggleOverflow(this)">Show more</button>
        <?php endif; ?>

    </div>

    <script type="text/javascript">
        <?php include_once(FCPATH . 'js/collapsible.js') ?>
    </script>
</div>

<?php endif; ?>
