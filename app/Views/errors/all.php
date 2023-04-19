<?php if (isset($errors) && !empty($errors)) : ?>
    <ul class="alert alert-warning rounded text-start">
    <?php foreach ($errors as $error) : ?>
        <li><?= $error ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
