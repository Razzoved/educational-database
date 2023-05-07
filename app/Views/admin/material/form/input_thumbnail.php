<?php
    /**
     * Partial view that serves as an uploader and presenter
     * for materials' thumbnail images.
     *
     * @param App\Entities\Resource $thumbnail current thumbnail
     */
    $rootPath = $thumbnail->getRootPath();
?>
<div clas="form__group">

    <!-- thumbnail view -->
    <image id="thumbnail"
        class="form__logo"
        style="width: 12rem; height: 12rem; object-fit: cover"
        src="<?= \App\Libraries\Resource::pathToURL($rootPath) ?>"
        alt="No image"
        onclick="document.getElementById('thumbnail-uploader').click()">
    </image>

    <input id="thumbnail-path" type="hidden" name="thumbnail" value="<?= $rootPath ?>">

    <!-- file uploader -->
    <input type="file"
        title='Upload thumbnail'
        id="thumbnail-uploader"
        name="thumbnail-uploader"
        accept="image/*"
        onchange="uploadThumbnail()"
        hidden>

</div>

<script>
    const fileSelector = document.getElementById('thumbnail-uploader');

    const newThumbnail = (filepath) => {
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

    const uploadThumbnail = () => {
        const formData = new FormData();
        const file = fileSelector.files[0];

        formData.append("file", file);
        formData.append('fileType', 'thumbnail');

        fileSelector.value = '';

        if (file === undefined) {
            return console.debug('thumbnail undefined')
        }

        fetch('<?= url_to("Admin\Resource::upload") ?>', { method: 'POST', body: formData })
            .then(response => {
                if (!response.ok) {
                    throw Error(response.statusText);
                }
                return response.json();
            })
            .then(response => newThumbnail(response.tmp_path))
            .catch(error => showError(error));
    }
</script>
