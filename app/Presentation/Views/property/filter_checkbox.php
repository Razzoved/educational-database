<div class="page__group">
    <button type="button" class="page__toggler" onclick="toggleGroup(this)">
        <i class="fa fa-bars"></i>
        Toggle filters
    </button>
    <button type="button" class="page__reset" onclick="submitSearch()">
        Apply filters
    </button>
    <button type="button" class="page__reset" onclick="resetFilters()">
        Reset filters
    </button>
    <?php foreach ($properties as $property) : ?>
        <?= view('property/collapsible', ['property' => $property, 'type' => 'checkbox', 'isFirstLevel' => true]) ?>
    <?php endforeach; ?>
</div>