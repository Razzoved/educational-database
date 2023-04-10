<div class="page__group">
    <button type="button" class="page__toggler" onclick="toggleGroup(this)">
        <i class="fa fa-bars"></i>
        Toggle filters
    </button>
    <button type="button" class="page__reset" onclick="resetFilters()">
        Reset filters
    </button>
    <?php foreach ($properties as $property) : ?>
        <?php if (isset($property->children) && !empty($property->children)) : ?>
            <?= view('components/property_as_collapsible', ['property' => $property, 'type' => 'checkbox']) ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
