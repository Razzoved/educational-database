<!-- group of MATERIALS shown as clickable links -->
<?= ($resources == []) ? "" : "<hr><h2>$title</h5>" ?>
<ul>
    <?php foreach ($resources as $resource) : ?>
    <li>
    <?php
        if (!$resource->isLink()) {
            echo '<img src="' . $resource->getFileThumbnail()->getPath() . '"></img>';
        }
        echo "<a href='" . $resource->getPath();
        echo ($resource->isLink()) ? "'>" : "'download>";
        $name = $resource->getName($resource->isLink());
        if (strlen($name) > 60) {
            echo substr($name, 0, 57) . '...';
        } else {
            echo $name;
        }
        echo "</a>";
    ?>
    </li>
    <?php endforeach; ?>
</ul>
