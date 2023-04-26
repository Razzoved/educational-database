<?php if ($type === 'button') : ?>
    <form method="get" action='<?= url_to('Material::index') ?>' style="display: block">
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
