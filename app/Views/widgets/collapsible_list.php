<li class="mb-1">

    <!-- toggler that shows or hides groups' value list -->
    <a class="btn btn-toggle align-items-center rounded collapsed"
            style="justify-content: center; display: flex"
            data-bs-toggle="collapse"
            data-bs-target="#<?= str_replace(' ', '_', $tag) ?>-collapse"
            aria-expanded="true">

        <!-- triangle right -->
        <i class="fa-solid fa-caret-right collapse-show"></i>

        <!-- triangle down -->
        <i class="fa-solid fa-caret-down collapse-hide"></i>

        <!-- property group tag -->
        <strong style="font-size: 1.2em;"><?= $tag ?></strong>
    </a>

    <!-- collapsible list -->
    <div class="collapse show" id='<?= str_replace(' ', '_', $tag) ?>-collapse'>
        <ul class="btn-toggle-nav list-unstyled">
            <!-- dynamic loading of values -->
            <?= ($type == 'button') ? view_cell('\App\Libraries\Property::buttons',
                                                ['tag' => $tag, 'values' => $values])
                                    : view_cell('\App\Libraries\Property::checkboxes',
                                                ['tag' => $tag, 'values' => $values]) ?>
        </ul>
    </div>

</li>
