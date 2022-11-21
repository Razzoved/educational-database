<!-- Sidebar, not shown on smaller screens, to use it
     wrap it inside of a form with any action button -->
<div class="flex-shrink-0 d-none d-lg-inline" style="width: 280px">
    <ul class="list-unstyled ps-0 sticky" style="height: fit-content">
        <!-- TODO: this should not do a post method, but a jquery? reset -->
        <li>
            <a class="btn btn-dark w-100 mb-2" href='/'>Reset all</a>
        </li>
        <?php foreach ($properties as $tag => $values) : ?>
            <?= view('widgets/collapsible_list', ['tag' => $tag, 'values' => $values, 'type' => 'checkbox']) ?>
        <?php endforeach; ?>
    </ul>
</div>
