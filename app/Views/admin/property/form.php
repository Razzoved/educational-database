<?= $this->extend('layouts/admin_form') ?>

<?= $this->section('content') ?>

<?php
    helper('form');
    $control = ['class' => 'form-control'];
    $label = ['class' => 'form-label'];
?>

<main class="form-edit">

    <?= $this->include('errors/validation') ?>

    <?= form_open_multipart('admin/tags/update') ?>

        <!-- tag -->
        <div class="form-edit-floating">
            <?= form_label('Tag', 'fTag', $label) ?>
            <?= form_input(['id' => 'fTag', 'name' => 'tag'], set_value('tag'), $control) ?>
        </div>

        <!-- value -->
        <div class="form-edit-floating">
            <?= form_label('Value', 'fValue', $label) ?>
            <?= form_input(['id' => 'fValue', 'name' => 'value'], set_value('value'), $control) ?>
        </div>

        <!-- hidden attributes (for editing) -->
        <?= form_hidden('id', set_value('id')) ?>

        <!-- buttons -->
        <div class="row g-0">
            <?= form_submit(['class' => 'col btn btn-lg btn-dark w-50 edit-mr'], 'Save') ?>
            <button type="button" class="col btn btn-lg btn-danger w-50" onclick="window.history.back()">Cancel</button>
        </div>

    <?= form_close() ?>

</main>

<?= $this->endSection() ?>
