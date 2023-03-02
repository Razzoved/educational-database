<div class="sidebar">
    <button type="button" class="icon" onclick="toggleSidebar()">
        <i class="fa fa-bars"></i>
        Toggle filters
    </button>
    <button type="button" class="sidebar-reset-btn" onclick="resetFilters()">
        Reset filters
    </button>
    <ul>
        <?php foreach ($properties as $tag => $values) : ?>
            <?= view('components/collapsible_list', ['tag' => $tag, 'values' => $values, 'type' => 'checkbox']) ?>
        <?php endforeach; ?>
    </ul>
</div>
