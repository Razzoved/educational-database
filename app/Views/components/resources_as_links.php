<!-- group of MATERIALS shown as clickable links -->
<?= ($resources == []) ? "" : "<hr><h5>$title</h5>" ?>
<ul class="list-unstyled ms-2">
    <?php foreach ($resources as $resource) : ?>
    <li>
    <?php
        echo "<span class=\"badge bg-primary me-1\">$resource->type</span>";
        echo "<a href='";
        echo $resource->getPath();
        echo ($resource->type === 'link') ? "'>" : "' download>";
        echo $resource->path;
        echo "</a>";
    ?>
    </li>
    <?php endforeach; ?>
</ul>
