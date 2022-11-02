<div>
    <h6><?= array_key_first($group) ?></h6>
    <div>
        <?php foreach ($group as $tag) : ?>
            <a src="" method="post"><?= $tag ?></a>
        <?php endforeach; ?>
    </div>
</div>