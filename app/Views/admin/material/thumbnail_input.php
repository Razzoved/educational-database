<?php
    /**
     * Partial view that generates thumbnail input in form.
     * It requires jQuery & dynamics javascript file to be loaded.
     *
     * Expects:
     * @param files files that already exist
     */

    $IMG_REGEX = '/.*\.(?:jpg|jpeg|tiff|gif|png|bmp)$/';
    $THUMBNAIL = \App\Libraries\Resources::pathToURL($thumbnail);
?>
<div style="align-items: center">

    <!-- thumbnail view -->
    <image id="thumbnail"
        class="img-fluid rounded edit-mr"
        style="width: 12rem; height: 12rem; object-fit: scale-down"
        src="<?= $THUMBNAIL ?>"
        alt="No image"
        onclick="document.getElementById('thumbnail-uploader').click()">
    </image>
    <input id="thumbnail-path" type="hidden" name="thumbnail" value="<?= $thumbnail ?>">

    <!-- file uploader -->
    <input id="thumbnail-uploader" name="thumbnail-uploader" type="file" onchange="uploadThumbnail()" hidden>

</div>

<script>
    function uploadThumbnail()
    {
        let formData = new FormData();

        let fileSelector = document.getElementById('thumbnail-uploader');
        let file = fileSelector.files[0];

        fileSelector.value = '';

        if (file === undefined || !file['name'].match(<?= $IMG_REGEX ?>)) {
            showError("Thumbnail must be an image!\n\n" + file['name']);
            return;
        }

        formData.append("file", file)

        $.ajax({
            url: '<?= base_url("admin/files/uploadThumbnail") ?>',
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(file) {
                newThumbnail(JSON.parse(file));
                console.log('success:', file);
            },
            error: (jqHXR) => showError(jqHXR)
        });
    }

    function newThumbnail(filepath)
    {
        if (filepath === undefined) {
            console.error('File does not exist');
            return;
        }

        let image = document.getElementById("thumbnail");
        let path = document.getElementById("thumbnail-path");

        if (typeof addToUnused === 'function' && path.value !== undefined && path.value !== '') {
            addToUnused(path.value);
        }

        image.src = '<?= base_url() ?>' + filepath;
        path.value = filepath;
    }
</script>
