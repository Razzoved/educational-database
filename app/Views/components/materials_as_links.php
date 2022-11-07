<!-- group of MATERIALS shown as clickable links -->
<?= ($materials == []) ? "" : "<hr><h5>$title</h5>" ?>
<ul class="list-unstyled ms-2">
    <?php foreach ($materials as $material) : ?>
    <li>
    <?php
        echo "<span class=\"badge bg-primary me-1\">$material->material_type</span>";
        echo "<a href='";
        echo $material->getPath();
        echo ($material->material_type != 'link') ? "'" : "' download";
        echo ">$material->material_title</a>";
    ?>
    </li>
    <?php endforeach; ?>
</ul>
