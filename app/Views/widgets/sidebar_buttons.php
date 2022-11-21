<!-- Sidebar, not shown on smaller screens -->
<div class="flex-shrink-0 d-none d-lg-inline" style="width: 280px">
    <ul class="list-unstyled ps-0" style="height: fit-content">
        <?php foreach ($properties as $tag => $values) : ?>
            <?= view('widgets/collapsible_list', ['tag' => $tag, 'values' => $values, 'type' => 'button']) ?>
        <?php endforeach; ?>
    </ul>
</div>
