<?php
    /**
     * TEMPLATE: renders a collapsible item on the page, if the item
     * has nested children, also renders a collapsible list.
     *
     * @param App\Entities\Property $item The item to render
     * @param string $type                The type of collapsible items to render
     * @param bool $isOverflow            Whether the item should be hidden by overflow flag
     */
    $isOverflow = $isOverflow ?? false;
    $tooltip = $property->description != "" ? "data-tooltip='{$property->description}'" : '';
?>

<li class="collapsible__item<?= $isOverflow ? ' collapsible__item--overflow' : '' ?>">

    <?php if (!empty($property->children)) : ?>

        <?= view('property/collapsible', ['property' => $property, 'type' => $type], ['saveData' => false]) ?>

    <?php elseif ($type === 'button') : ?>

        <form class="filter" method="get" action='<?= url_to('Material::index') ?>'>
            <input type="hidden"
                name="filter"
                value="<?= $property->id ?>">
            <button class="filter__label" type="submit"><?= esc($property->value) ?></button>
        </form>

    <?php else : ?>

        <fieldset class="filter" <?= $tooltip ?>>
            <input class="filter__checkbox"
                type="checkbox"
                name="filter"
                value="<?= $property->value ?>"
                id="filter_<?= $property->id ?>">
            </input>
            <label class="filter__label" for="filter_<?= $property->id ?>">
                <?= esc($property->value) ?>
            </label>
        </fieldset>

    <?php endif; ?>

</li>
