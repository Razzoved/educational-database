<!-- Sidebar, not shown on smaller screens -->
<div class="flex-shrink-0 p-3 bg-light d-none d-lg-inline" style="height: 100vh; width: 300px;">
    <ul class="list-unstyled ps-0">
        <?php foreach ($properties as $tag => $values) : ?>
            <?= view('widgets/collapsible_list', ['tag' => $tag, 'values' => $values, 'type' => 'button']) ?>
        <?php endforeach; ?>
    </ul>
</div>
