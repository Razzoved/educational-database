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
        style="width: 15rem; height: 15rem; object-fit: cover"
        src="<?= \App\Libraries\Resource::pathToURL($rootPath) ?>"
        alt="No image"
        onclick="document.getElementById('thumbnail-uploader').click()"
        title="Image is cropped and resized to 512x512; provide an image with 1:1 aspect ratio if possible!">
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
    <?php include_once(FCPATH . 'js/fetch.js'); ?>

    const thumbnailImg      = document.getElementById("thumbnail");
    const thumbnailPath     = document.getElementById("thumbnail-path");
    const thumbnailSelector = document.getElementById('thumbnail-uploader');

    const newThumbnail = (resource) => {
        if (resource === undefined) {
            return console.error('File does not exist');
        }
        if (typeof addToUnused === 'function' && thumbnailPath.value !== '') {
            addToUnused(thumbnailPath.value);
        }
        thumbnailImg.src = '<?= base_url() ?>' + resource.tmp_path;
        thumbnailPath.value = resource.tmp_path;
    }

    const uploadThumbnail = () => upload(
        '<?= url_to("Admin\Resource::upload") ?>',
        {
            selector: thumbnailSelector,
            fileType: 'thumbnail'
        },
        newThumbnail,
    );
</script>
