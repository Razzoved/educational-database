<?php if (isset($errors) && !empty($errors)) : ?>
    <ul style="padding: 1rem">
    <?php foreach ($errors as $error) : ?>
        <li style="color: red"><?= $error ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
