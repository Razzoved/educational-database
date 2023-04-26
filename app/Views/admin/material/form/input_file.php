<?php
    /**
     * Partial view that generates file input in form.
     * It requires jQuery & dynamics javascript file to be loaded.
     *
     * Expects:
     * @param files files that already exist
     */

    $IMG_REGEX = '/.*\.(?:jpg|jpeg|tiff|gif|png|bmp)$/';
?>
<div class="form__group">

    <!-- file uploader -->
    <button class="form__input" type="button" onclick="document.getElementById('file-uploader').click()">Add</button>
    <input id="file-uploader" type="file" onchange="uploadFile()" hidden>

    <div class="form__group form__group--horizontal" id="file-group">
    <?php
        foreach (array_keys($files) as $index => $file) {
            echo view('admin/material/form/item_file', [
                'id' => $index,
                'value' => $files[$file],
                'path' => $file,
            ]);
        }
    ?>
    </div>

</div>

<script>
    function uploadFile()
    {
        let target = document.getElementById('file-group');
        let id = parseInt(target.lastElementChild?.id.replace(/^\D+/g, '') ?? '-1') + 1;

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

        formData.append("id", id);
        formData.append("file", file);
        console.log("SENDING", file);

        $.ajax({
            url: '<?= url_to("Admin\Resource::upload") ?>',
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(views) {
                views = JSON.parse(views);
                for (var name in views) {
                    let newDiv = document.createElement('div');
                    newDiv.innerHTML = views[name];
                    target.appendChild(newDiv.firstElementChild);
                    console.log('SUCCESS:', name);
                }
            },
            error: (jqHXR) => showError(jqHXR)
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

    function removeFile(id)
    {
        let element = document.getElementById(id);
        let path = element.getAttribute('data-value');

        if (typeof addToUnused === 'function' && path !== undefined && path !== '') {
            addToUnused(path);
        }
        element.remove();
    }
</script>
