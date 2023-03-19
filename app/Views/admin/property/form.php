<?= $this->extend('layouts/form') ?>

<?= $this->section('content') ?>

<?php
    helper('form');
    $control = ['class' => 'form-control'];
    $label = ['class' => 'form-label'];
?>

<div class="page">
    <?php helper('form') ?> <!-- set_value function -->

    <form class="form" method="post" action="<?= base_url('admin/tags/update') ?>">
        <!-- errors -->
        <div class="form__group form__group--centered">
            <h1 class="form__title">Edit tag</h1>
            <?= $this->include('errors/validation') ?>
        </div>

        <!-- inputs -->
        <fieldset class="form__group">
            <label for="tag" class="form__label">Tag</label>
            <input class="form__input"
                type="text"
                name="tag"
                placeholder="Enter tag"
                value="<?= set_value('tag') ?>"
                required>
            <label for="value" class="form__label">Value</label>
            <input class="form__input"
                type="text"
                name="value"
                placeholder="Enter value"
                value="<?= set_value('value') ?>"
                required>
        </fieldset>

        <!-- hidden attributes (for editing) -->
        <input type="hidden" id="id" value="<?= set_value('id') ?>">

        <!-- actions -->
        <div class="form__group form__group--horizontal">
            <button type="submit" class="form__submit">Save</button>
            <button type="button" class="form__cancel" onclick="window.history.back()">Cancel</button>
        </div>
    </form>

</div>

<?= $this->endSection() ?>
