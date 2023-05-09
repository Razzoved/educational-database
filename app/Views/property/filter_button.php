<div class="page__group">
    <button type="button" class="page__toggler" onclick="toggleGroup(this)">
        <i class="fa fa-bars"></i>
        Toggle tags
    </button>
    <?php foreach ($properties as $property) : ?>
        <?php if (isset($property->children) && !empty($property->children)) : ?>
            <?= view('property/collapsible', ['property' => $property, 'type' => 'button', 'isFirstLevel' => true]) ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
