<!-- group of MATERIALS shown as clickable links -->

<ul>
    <?php foreach ($materials as $material) : ?>
    <li>
    <?php
        // TODO: make it pretty
        echo "<a href=\"$material->material_path\"";
        if ($material->material_type != 'link') {
            echo "download";
        }
        echo "><span class=\"badge badge-secondary\">$material->material_type</span> $material->material_title</a>";
    ?>
    </li>
    <?php endforeach; ?>
</ul>
