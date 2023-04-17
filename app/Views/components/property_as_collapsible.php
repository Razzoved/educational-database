<?php
    /** Renders a collapsible list onto a page:
     *  Data format: [priorities][properties] ... [properties]
     *
     *  Expects:
     *  @param string $type - buttons or checkboxes
     *  @param Property $property
     */

use App\Entities\Property;

?>

<?php if (!isset($property->children) || empty($property->children)) : ?>

    <?php if ($type === 'button') : ?>
        <form method="get" action='<?= base_url('/1') ?>' style="display: block">
            <input type="hidden"
                name="#<?= $property->tag ?>[<?= $property->id ?>]"
                value="on">
            <button type="submit"><?= esc($property->value) ?></button>
        </form>
    <?php else : ?>
        <input class="filter" type="checkbox"
            name="#<?= $property->tag ?>[<?= $property->id ?>]"
            id="#<?= $property->tag ?>[<?= $property->id ?>]">
        </input>
        <label for="#<?= $property->tag ?>[<?= $property->id ?>]">
            <?= esc($property->value) ?>
        </label>
    <?php endif; ?>

<?php else : ?>

    <section class="collapsible collapsible--no-overflow<?= $property->tag != 0 ? ' collapsible--small collapsible--collapsed' : '' ?>">
        <button class="collapsible__toggle" type="button" onclick="toggleCollapsible(this)">
            <i class="collapsible__indicator fa-solid fa-caret-right"></i>
            <h2 class="collapsible__title"><?= $property->value ?></h2>
        </button>

        <?php $index = 0; ?>
        <ul class="collapsible__target">
            <?php $parent = $property->tag != 0
                ? array(new Property([
                    'id' => $property->id,
                    'tag' => $property->tag,
                    'value' => $property->value]))
                : []; ?>
            <?php foreach (array_merge($parent, $property->children) as $child) : ?>
                <li class="collapsible__item<?= $index > 5 ? ' collapsible__item--overflow' : '' ?> tooltip">
                    <?php if ($child->description && $child->description !== '') : ?>
                        <span class="tooltip__text"><?= esc($child->description) ?></span>
                    <?php endif; ?>
                    <?= view('components/property_as_collapsible', ['property' => $child, 'type' => $type]) ?>
                </li>
                <?php $index++; ?>
            <?php endforeach; ?>
        </ul>

        <?php if ($index > 5) : ?>
            <button type="button" class="collapsible__toggle-overflow" onclick="toggleOverflow(this)">Show more</button>
        <?php endif; ?>

        <script>
            <?php include_once(ROOTPATH . '/public/js/collapsible.js') ?>
            <?php include_once(ROOTPATH . '/public/js/tooltip.js') ?>
        </script>
    </section>

<?php endif; ?>
