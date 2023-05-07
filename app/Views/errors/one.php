<?php if (isset($errors) && !empty($errors)) : ?>
    <div style="padding: 1rem">
        <p style="color: red"><?= $errors[array_key_first($errors)] ?></p>
    </div>
<?php endif; ?>
