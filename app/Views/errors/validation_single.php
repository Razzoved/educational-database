<?php if (isset($errors) && !empty($errors)) : ?>
    <div class="alert alert-warning rounded text-start">
        <p><?= $errors[array_key_first($errors)] ?></p>
    </div>
<?php endif; ?>
