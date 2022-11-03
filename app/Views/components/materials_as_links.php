<!-- group of MATERIALS shown as clickable links -->

<ul>
    <?php foreach ($materials as $material) : ?>
    <li>
    <?php
        // TODO: make it pretty
        if ($material->material_type == 'link') {
            echo "<a href=$material->material_path>$material->material_title</a>";
        } else {
            echo "<p><a href=$material->material_path download>$material->material_title</a> of type: $material->material_type</p>";
        }
    ?>
    </li>
    <?php endforeach; ?>
</ul>
