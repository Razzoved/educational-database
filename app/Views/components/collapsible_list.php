<section class="collapsible collapsible--no-overflow">
    <button class="collapsible__toggle" type="button" onclick="toggleCollapsible(this)">
        <i class="collapsible__indicator fa-solid fa-caret-right"></i>
        <h2 class="collapsible__title"><?= $tag ?></h2>
    </button>

    <ul class="collapsible__target">
    <?php
        $index = 0;
        foreach ($values as $value) {
            echo '<li class="collapsible__item' . ($index > 5 ? ' collapsible__item--overflow">' : '">');
            echo view('components/property_' . $type, ['tag' => $tag, 'value' => $value]);
            echo '</li>';
            $index++;
        }
    ?>
    </ul>
    <?php
        if ($index > 5) {
            echo '<button type="button" class="collapsible__toggle-overflow" onclick="toggleOverflow(this)">Show more</button>';
        }
    ?>
    <script><?= include_once(ROOTPATH . '/public/js/collapsible.js') ?></script>
</section>
