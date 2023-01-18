<?php if (isset($validation) && !empty($validation->getErrors())) : ?>
    <div class="alert alert-warning rounded text-start">
        <?= $validation->listErrors() ?>
    </div>
<?php endif; ?>
