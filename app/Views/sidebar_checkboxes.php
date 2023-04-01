<div class="page__group">
    <button type="button" class="page__toggler" onclick="toggleGroup(this)">
        <i class="fa fa-bars"></i>
        Toggle filters
    </button>
    <button type="button" class="page__reset" onclick="resetFilters()">
        Reset filters
    </button>
    <?php foreach ($properties as $tag => $values) : ?>
        <?php if (!empty($values)) : ?>
            <?= view('components/collapsible_list', ['tag' => $tag, 'values' => $values, 'type' => 'checkbox']) ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
