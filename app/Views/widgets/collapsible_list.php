<li class="clps<?= ($type == 'button') ? '' : ' clps-closed' ?>">
    <!-- toggler that shows or hides groups' value list -->
    <a class="clps_expand">

        <!-- triangle right -->
        <i class="fa-solid fa-caret-right"></i>

        <!-- property group tag -->
        <strong style="font-size: 1.2em;"><?= $tag ?></strong>
    </a>

    <ul class="clps_list">
        <!-- dynamic loading of values -->
        <?= ($type == 'button') ? view_cell('\App\Libraries\Property::buttons',
                                            ['tag' => $tag, 'values' => $values])
                                : view_cell('\App\Libraries\Property::checkboxes',
                                            ['tag' => $tag, 'values' => $values]) ?>
    </ul>
    <script src=<?= base_url('/js/collapsible.js') ?>></script>
</li>
