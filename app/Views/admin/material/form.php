<?php
    /**
     * View for a material editor. This is not, as opposed to the other
     * forms, not a modal, but a separate view.
     *
     * @param ?\App\Entities\Material $material material to be edited or null
     * @param array $errors                     error messages
     */
    use \App\Entities\Cast\StatusCast;
    use \App\Entities\Material;
    use \App\Entities\Resource;

    $path = 'admin/material/form';

    $errors = $errors ?? [];
    $material = isset($material) && is_a($material, Material::class)
        ? $material
        : new Material();

    helper('form');
?>

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
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page">

    <h1 class="page__title">Material editor</h1>

    <div class="page__content">

        <form class="form form--big-gaps" method="post" method="post" action="<?= url_to('Admin\MaterialEditor::save') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <?= $this->include('errors/all', ['errors' => $errors]) ?>

            <!-- thumbnail, basic data -->
            <div class="form__group form__group--horizontal">

                <fieldset class="form__group">
                    <label for="thumbnail" class="form__label form__label--small">Thumbnail (click to edit)</label>
                    <?= view("{$path}/input_thumbnail", ['thumbnail' => $material->getThumbnail() ])?>
                </fieldset>

                <fieldset class="form__group form__group--major">
                    <!-- title -->
                    <label for="title" class="form__label">Title</label>
                    <input class="form__input"
                        type="text"
                        name="title"
                        placeholder="Enter title"
                        value="<?= $material->title ?>"
                        required>
                    <!-- status -->
                    <label for="status" class="form__label">Status</label>
                    <?= form_dropdown(
                        ['id' => 'status', 'name' => 'status', 'class' => 'form__select'],
                        StatusCast::VALID_VALUES,
                        StatusCast::getIndex($material->status ?? 0)
                    ) ?>

                    <script type="text/javascript">console.log('<?= $material->status ?>')</script>
                </fieldset>

            </div>

            <!-- tags selector -->
            <fieldset class="form__group">
                <label for="properties" class="form__label">Tags</label>
                <?= view("{$path}/input_property", [
                    'properties' => $material->properties ?? [],
                    'available'  => $availableProperties ?? [],
                ]) ?>
            </fieldset>

            <!-- content editor provided externally -->
            <fieldset class="form__group">
                <label for="tiny" class="form__label">Content</label>
                <textarea id="tiny" name="content" cols="60" rows="20"><?= $material->content ?></textarea>
            </fieldset>

            <!-- form group attachments -->
            <fieldset class="form__group">
                <label for="links" class="form__label">Links to relevant sites</label>
                <?= view("{$path}/input_link", ['links' => $material->getLinks()]) ?>
            </fieldset>

            <fieldset class="form__group">
                <label for="files" class="form__label">Attached files</label>
                <?= view("{$path}/input_file", ['files' => $material->getFiles()]) ?>
            </fieldset>

            <fieldset class="form__group">
                <label for="relations" class="form__label">Related materials</label>
                <?= view("{$path}/input_relation", ['relations' => $material->related]) ?>
            </fieldset>

            <!-- hidden attributes (for editing) -->
            <?= form_hidden('id', isset($material->id) && $material->id !== 0 ? $material->id : '') ?>
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
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script type='text/javascript'>
    const ERROR_MODAL = `<?= view('errors/modal', []) ?>`;

    HTMLInputElement.prototype.setInvalid = function(invalid = true) {
        if (!invalid) {
            this.classList.remove('form__input--invalid');
        } else {
            this.classList.add('form__input--invalid');
        }
        return !invalid;
    }

    const addToUnused = (filepath) => {
        let unused = document.getElementById('unused-files');

        let newInput = document.createElement('input');
        newInput.setAttribute('name', 'unused_files[]');
        newInput.setAttribute('type', 'hidden');
        newInput.setAttribute('value', filepath.replace('<?= base_url() ?>', '').replace(/^\\/, ''));

        unused.appendChild(newInput);
    }

    const goBack = () => {
        let historySize = document.querySelector('input[name="history"]');
        if (historySize && historySize.value && historySize.value > 0) {
            window.history.go(-historySize.value);
        } else {
            window.history.back();
        }
    }
</script>

<?= $this->endSection() ?>
