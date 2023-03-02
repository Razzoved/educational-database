<li class="collapsible overflow-closed">
    <!-- toggler that shows or hides groups' value list -->
    <a class="collapsible-btn" onclick="toggleGroup(this)">
        <i class="fa-solid fa-caret-right"></i>
        <strong style="font-size: 1.2em;"><?= $tag ?></strong>
    </a>

    <ul class="collapsible-list">
    <?php
        $index = 0;
        foreach ($values as $value) {
            echo view('components/property_' . $type, ['tag' => $tag, 'value' => $value, 'overflow' => $index > 5]);
            $index++;
        }
        if ($index > 5) {
            echo '<li>';
            echo '<button type="button" class="overflow-btn" onclick="toggleOverflow(this)">Show more</button>';
            echo '</li>';
        }
    ?>
    </ul>
    <script><?= include_once(ROOTPATH . '/public/js/collapsible.js') ?></script>
</li>
