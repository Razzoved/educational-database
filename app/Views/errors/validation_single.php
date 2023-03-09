<?php if (isset($validation) && !empty($validation->getErrors())) : ?>
    <?php
        $error = array_key_first($validation->getErrors());
    ?>
    <div class="alert alert-warning rounded text-start">
        <?= $validation->showError($error) ?>
    </div>
<?php endif; ?>
