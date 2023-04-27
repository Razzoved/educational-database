<?= $this->extend('layouts/form') ?>

<?= $this->section('header') ?>
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/69ihqfomziifwjc1jznu6ynf4vn7l4zgj6f4a4zxc1blk1p2/tinymce/6/tinymce.min.js"
            referrerpolicy="origin">
    </script>
    <script>
        tinymce.init({
            selector: 'textarea#tiny',
            plugins: [
            'advlist','autolink',
            'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
            'fullscreen','insertdatetime','media','table','help','wordcount'
            ],
            toolbar: 'undo redo | a11ycheck casechange blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify |' +
            'bullist numlist checklist outdent indent | removeformat | code table help',
        })
    </script>
    <!-- end of TinyMCE -->

    <script type="text/javascript" src="<?= base_url('public/js/dynamics.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php helper('form') ?>

<?php
    $properties = isset($_POST['properties']) ? $_POST['properties'] : null;
    $thumbnail = isset($_POST['thumbnail']) ? $_POST['thumbnail'] : null;

    $control = ['class' => 'form-control'];
    $label = ['class' => 'form-label'];
?>

<div class="page">
    <form class="form" method="post" method="post" action="<?= url_to('Admin\MaterialEditor::save') ?>" enctype="multipart/form-data">

        <!-- title -->
        <div class="form__group form__group--centered form__group--separated">
            <h1 class="form__title">Material editor</h1>
            <?= $this->include('errors/all') ?>
        </div>

        <!-- thumbnail, basic data -->
        <fieldset class="form__group form__group--horizontal">

            <div class="form__group">
                <label for="thumbnail" class="form__label form__label--small">
                    Thumbnail (click to edit)
                </label>
                <?= view('admin/material/thumbnail_input', ['thumbnail' => $thumbnail]) ?>
            </div>

            <div class="form__group form__group--major">
                <!-- title -->
                <label for="title" class="form__label">Title</label>
                <input class="form__input"
                    type="text"
                    name="title"
                    placeholder="Enter title"
                    value="<?= set_value('title') ?>"
                    required>
                <!-- status -->
                <label for="status" class="form__label">Status</label>
                <?= form_dropdown(['id' => 'status', 'name' => 'status'],
                    \App\Entities\Cast\StatusCast::VALID_VALUES,
                    set_value('status'),
                    ['class' => 'form__input']) ?>
            </div>
        </fieldset>

        <fieldset class="form__group">
            <label for="properties" class="form__label">Tags</label>
            <?= view('admin/material/property_selector', ['used' => $properties, 'available' => $available_properties]) ?>

            <label for="tiny" class="form__label">Content</label>
            <textarea id="tiny" name="content" cols="60" rows="20"><?= set_value('content', '', false) ?></textarea>

            <label for="links" class="form__label">Links to relevant sites</label>
            <?= view('admin/material/link_input', ['label' => $label, 'links' => set_value('links', [], false)]) ?>

            <label for="files" class="form__label">Attached files</label>
            <?= view('admin/material/file_input', ['label' => $label, 'files' => set_value('files', [], false)]) ?>

            <label for="relations" class="form__label">Related materials</label>
            <?= view('admin/material/relation_input', ['label' => $label, 'available' => $available_relations, 'relations' => set_value('relations', [], false)]) ?>
        </fieldset>

        <!-- hidden attributes (for editing) -->
        <?= form_hidden('id', set_value('id')) ?>
        <?= form_hidden('history', set_value('history', 0, false) + 1) ?>

        <div id="unused-files" hidden>
        </div>

        <!-- buttons -->
        <div class="form__group form__group--horizontal">
            <button type="submit" class="form__submit">Save</button>
            <button type="button" class="form__cancel" onclick="goBack()">Cancel</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script type='text/javascript'>
    function addToUnused(filepath)
    {
        let unused = document.getElementById('unused-files');

        let newInput = document.createElement('input');
        newInput.setAttribute('name', 'unused_files[]');
        newInput.setAttribute('type', 'hidden');
        newInput.setAttribute('value', filepath.replace('<?= base_url() ?>', '').replace('<?= base_url() ?>\\', ''));

        unused.appendChild(newInput);
    }

    function goBack()
    {
        let historySize = document.querySelector('input[name="history"]');
        if (historySize && historySize.value && historySize.value > 0) {
            window.history.go(-historySize.value);
        } else {
            window.history.back();
        }
    }
</script>

<?= $this->endSection() ?>
