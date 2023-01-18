<!-- Sidebar, not shown on smaller screens, to use it
     wrap it inside of a form with any action button -->
<div class="flex-shrink-0 <?= $visibility ?? '' ?>">
    <ul class="list-unstyled ps-0 sticky" style="height: fit-content">
        <li>
            <button type="button" class="btn btn-dark w-100 mb-2" onclick="{ document.querySelectorAll('.clps input[type=checkbox]').forEach(e => e.checked=false); document.querySelectorAll('input[name=search]').forEach(e => e.value=''); }">Reset filters</button>
        </li>
        <?php foreach ($properties as $tag => $values) : ?>
            <?= view('widgets/collapsible_list', ['tag' => $tag, 'values' => $values, 'type' => 'checkbox']) ?>
        <?php endforeach; ?>
    </ul>
</div>
