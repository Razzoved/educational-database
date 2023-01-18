<li class="clps">
    <!-- toggler that shows or hides groups' value list -->
    <a class="clps_expand" onclick="toggleGroup(this)">

        <!-- triangle right -->
        <i class="fa-solid fa-caret-right"></i>

        <!-- property group tag -->
        <strong style="font-size: 1.2em;"><?= $tag ?></strong>
    </a>

    <ul class="clps_list clps-closed">
        <!-- dynamic loading of values -->
        <?= ($type == 'button') ? view_cell('\App\Libraries\Property::buttons',
                                            ['tag' => $tag, 'values' => $values])
                                : view_cell('\App\Libraries\Property::checkboxes',
                                            ['tag' => $tag, 'values' => $values]) ?>
    </ul>
    <script><?= include_once(ROOTPATH . '/public/js/collapsible.js') ?></script>
</li>
