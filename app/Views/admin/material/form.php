<?= $this->extend('layouts/admin_form') ?>

<?= $this->section('header') ?>

    <!-- custom styles -->
    <link href="<?= base_url('public/css/property_selector.css') ?>" rel="stylesheet" type="text/css">

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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?= base_url('public/js/dynamics.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('public/js/property_selector.js') ?>"></script>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php helper('form') ?>

<?php
    $properties = isset($_POST['properties']) ? $_POST['properties'] : null;
    $thumbnail = isset($_POST['thumbnail']) ? $_POST['thumbnail'] : null;

    $control = ['class' => 'form-control'];
    $label = ['class' => 'form-label'];
?>

<main class="form-edit">

    <?= $this->include('errors/validation') ?>

    <?= form_open_multipart('admin/materials/edit') ?>

        <div class="row g-0">
            <!-- thumbnail -->
            <div class="form-edit-floating col-auto edit-mr">
                <?= form_label('Thumbnail (click to edit)', 'fThumbnail', $label) ?>
                <?= view('admin/thumbnail_input', ['label' => $label, 'thumbnail' => $thumbnail]) ?>
            </div>
            <div class="col">
                <!-- title -->
                <div class="form-edit-floating">
                    <?= form_label('Title', 'fTitle', $label) ?>
                    <?= form_input(['id' => 'fTitle', 'name' => 'title'], set_value('title'), $control) ?>
                </div>
                <!-- sender + status -->
                <div class="row g-0">
                    <div class="col form-edit-floating edit-mr">
                        <?= form_label('Sender', 'fSender', $label) ?>
                        <?= form_input(['id' => 'fSender', 'name' => 'author'], set_value('author'), $control) ?>
                    </div>
                    <div class="col form-edit-floating">
                        <?= form_label('Status', 'fStatus', $label) ?>
                        <?= form_dropdown(['id' => 'fStatus', 'name' => 'status'],
                            \App\Entities\Cast\StatusCast::VALID_VALUES,
                            \App\Entities\Cast\StatusCast::getIndex(set_value('status')),
                            $control) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- tags -->
        <div class="form-edit-floating">
            <?= form_label('Tags', 'properties', $label) ?>
            <?= view('admin/property/selector', ['used' => $properties, 'available' => $available_properties]) ?>
        </div>

        <!-- content -->
        <?= form_textarea(['id' => 'tiny', 'name' => 'content'], set_value('content', '', false)) ?>

        <!-- links -->
        <div class="form-edit-floating">
            <?= form_label('Links to relevant sites', 'links', $label) ?>
            <?= view('admin/link_input', ['label' => $label, 'links' => set_value('links', [], false)]) ?>
        </div>

        <!-- files -->
        <div class="form-edit-floating">
            <?= form_label('Downloadable files', 'files', $label) ?>
            <?= view('admin/file_input', ['label' => $label, 'files' => set_value('files', [], false)]) ?>
        </div>

        <!-- related materials -->
        <div class="form-edit-floating">
            <?= form_label('Related materials', 'relations', $label) ?>
            <?= view('admin/relation_input', ['label' => $label, 'available' => $available_relations, 'relations' => set_value('relations', [], false)]) ?>
        </div>

        <!-- hidden attributes (for editing) -->
        <?= form_hidden('id', set_value('id')) ?>
        <div id="unused-files">
        </div>

        <!-- buttons -->
        <div class="row g-0">
            <?= form_submit(['class' => 'col btn btn-lg btn-dark w-50 edit-mr'], 'Save') ?>
            <button type="button" class="col btn btn-lg btn-danger w-50" onclick="window.history.back()">Cancel</button>
        </div>

    <?= form_close() ?>

</main>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script type='text/javascript'>
    function addToUnused(filepath)
    {
        let unused = document.getElementById('unused-files');

        let newInput = document.createElement('input');
        newInput.setAttribute('name', 'unused_files[]');
        newInput.setAttribute('type', 'hidden');
        newInput.setAttribute('value', filepath.replace('<?= base_url() ?>/', '').replace('<?= base_url() ?>\\', ''));

        unused.appendChild(newInput);
    }
</script>

<?= $this->endSection() ?>
