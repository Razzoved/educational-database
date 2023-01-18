<?php
    /**
     * Partial view that generates file input in form.
     * It requires jQuery & dynamics javascript file to be loaded.
     *
     * Expects:
     * @param files files that already exist
     */

    $IMG_REGEX = '/.*\.(?:jpg|gif|png|bmp)$/';
?>
<div class="dynamic-input">

    <!-- file uploader -->
    <div class="row g-0">
        <input id="file-uploader" type="file" onchange="uploadFile()" hidden>
        <button class="btn btn-md btn-secondary col-2" type="button" onclick="document.getElementById('file-uploader').click()">Add</button>
    </div>

    <!-- hidden template for js copying -->
    <?= view('admin/file_template', ['id' => null, 'value' => null, 'hidden' => true, 'readonly' => true]) ?>

    <div id="file-group" class="wrapper">
    <?php
        // echo var_dump($files);
        $index = 0;
        foreach (array_keys($files) as $file) {
            if ($file === 'fallback') continue;
            echo view('admin/file_template', [
                'id' => $index,
                'value' => $files[$file],
                'path' => $file,
                'image' => preg_match($IMG_REGEX, $file) ? $file : null,
                'hidden' => false,
                'readonly' => true
            ]);
            $index++;
        }
    ?>
    </div>

</div>

<script>
    function uploadFile()
    {
        let formData = new FormData();

        let fileSelector = document.getElementById('file-uploader');
        let file = fileSelector.files[0];

        fileSelector.value = '';

        if (file === undefined) {
            console.log("NO FILE SELECTED");
            return;
        }

        if (!checkIfUnique(file.name)) {
            console.log("FILE ALREADY ADDED");
            return;
        }

        formData.append("file", file)
        console.log("SENDING", file);

        $.ajax({
            url: '<?= base_url('admin/files/upload') ?>',
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(files) {
                files = JSON.parse(files);
                for (var name in files) {
                    newFile(name, files[name]);
                    console.log('success:', name);
                }
            },
            error: function(status) {
                alert('TODO: modal\nUnable to upload -> ' + status.statusText);
            },
        });
    }

    function checkIfUnique(filename)
    {
        let fileGroup = document.getElementById('file-group').querySelectorAll('input');
        for (var key in fileGroup) {
            if (filename === fileGroup[key].value) return false;
        }
        return true;
    }

    function newFile(filename, filepath)
    {
        if (filename === undefined || filename === "") {
            console.error('File name is empty', filepath);
            return;
        }
        if (filepath === undefined || filepath === "") {
            console.error(filename, 'File path is empty');
            return;
        }

        let container = document.getElementById('file-group');
        let newDiv = document.getElementById("file-template").cloneNode(true);
        let id = `file-${parseInt(container.lastElementChild?.id.replace(/^\D+/g, '') ?? '-1') + 1}`;

        modifyImage(newDiv, filepath);
        modifyName(newDiv, filename, filepath);
        modifyButton(newDiv, filepath, id);

        newDiv.id = id;
        newDiv.hidden = false;
        container.appendChild(newDiv);
    }

    function modifyImage(div, src)
    {
        if (src === undefined || !src.match(<?= $IMG_REGEX ?>)) {
            return; // not an image file (TODO: file type images)
        }

        let image = div.querySelector('img');

        if (image == undefined) {
            console.warn("image html not found");
            return;
        }

        image.src = '<?= base_url() ?>' + '/' + src;
    }

    function modifyName(div, filename, filepath)
    {
        let name = div.querySelector('input');

        if (name == undefined) {
            console.warn("name html not found");
            return;
        }

        name.disabled = null;
        name.readOnly = true;
        name.required = true;
        name.value = filename;
        name.name = name.name.replace('fallback', filepath);
    }

    function modifyButton(div, filepath, id)
    {
        let button = div.querySelector('button');

        if (button == undefined) {
            console.warn("button not found");
            return;
        }

        button.onclick = () => removeFile(id, filepath);
    }

    function removeFile(id, filepath) {
        removeById(id);
        if (typeof addToUnused === 'function' && filepath !== undefined && filepath !== '') {
            addToUnused(filepath);
        }
    }
</script>
