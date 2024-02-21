<?php
    /**
     * Partial view that generates file input in form.
     *
     * Expects:
     * @param array $files An array of Resource objects
     */
?>
<div class="form__group">

    <!-- file uploader -->
    <button class="form__button form__button--large" type="button" onclick="document.getElementById('file-uploader').click()">Add</button>
    <input id="file-uploader" type="file" onchange="uploadFile()" hidden>

    <div class="form__group form__group--horizontal-flex" id="file-group">
    <?php foreach ($files as $key => $resource) {
        $rootPath = $resource->getRootPath();
        echo view('admin/material/form/item_file', [
            'id'    => $key,
            'path'  => $rootPath,
            'value' => basename($resource->path),
            'imageURL' => \App\Libraries\Resource::pathToFileURL($rootPath)
        ]);
    } ?>
    </div>

</div>

<script>
    <?php include_once(FCPATH . 'js/fetch.js'); ?>

    const fileSelector = document.getElementById('file-uploader');
    const fileGroup = document.getElementById('file-group');

    const uniqueFile = () => {
        const files = fileGroup.querySelectorAll('input');
        for (var key in files) {
            if (fileSelector.files[0].name === files[key].value) return false;
        }
        return true;
    }

    const newFile = (resource) => {
        if (resource === undefined) {
            return console.error('File does not exist');
        }

        const template = `<?= view('admin/material/form/item_file') ?>`.fill({
            id: crypto.randomUUID(),
            path: resource.tmp_path,
            value: resource.path,
            imageURL: resource.imageURL ?? '<?= \App\Entities\Resource::getDefaultImage()->getURL() ?>'
        })

        fileGroup.insertAdjacentHTML('beforeend', template);
    }

    const uploadFile = () => uniqueFile() && upload(
        '<?= url_to("Admin\Resource::upload") ?>',
        {
            selector: fileSelector,
            fileType: 'file'
        },
        newFile
    );

    const removeFile = (id) => {
        let element = document.getElementById(id);
        let path = element.querySelector('input').name.replace(/^file\[/, '').replace(/\]$/, '');
        if (typeof addToUnused === 'function' && path !== '') {
            addToUnused(path);
        }
        element.remove();
    }
</script>
