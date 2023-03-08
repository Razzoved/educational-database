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
<div class="dynamic-input">

    <!-- file uploader -->
    <div class="row g-0">
        <input id="file-uploader" type="file" onchange="uploadFile()" hidden>
        <button class="btn btn-md btn-secondary col-2" type="button" onclick="document.getElementById('file-uploader').click()">Add</button>
    </div>

    <div id="file-group" class="wrapper">
    <?php
        $index = 0;
        foreach (array_keys($files) as $file) {
            echo view('admin/file_template', [
                'id' => $index,
                'value' => $files[$file],
                'path' => $file,
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
            url: '<?= base_url('admin/files/upload') ?>',
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
        $.ajax({
            type: 'POST',
            url: '<?= base_url('admin/files/delete') ?>',
            dataType: 'json',
            data: {path: path},
            success: function(unused) {
                element.remove();
            },
            error: (jqXHR) => showError(jqXHR)
        })
    }
</script>
