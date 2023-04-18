<div class="page__group">
    <button type="button" class="page__toggler" onclick="toggleGroup(this)">
        <i class="fa fa-bars"></i>
        Toggle tags
    </button>
    <?php foreach ($properties as $property) : ?>
        <?php if (isset($property->children) && !empty($property->children)) : ?>
            <?= view('components/property_as_collapsible', ['property' => $property, 'type' => 'button']) ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
