<!-- group of MATERIALS shown as clickable links -->

<ul class="list-unstyled">
    <?php foreach ($materials as $material) : ?>
    <li>
    <?php
        echo "<span class=\"badge bg-secondary\">$material->material_type</span>";
        echo "  ";
        echo "<a href=\"$material->material_path\"";
        if ($material->material_type != 'link') {
            echo "download";
        }
        echo ">$material->material_title</a>";
    ?>
    </li>
    <?php endforeach; ?>
</ul>
