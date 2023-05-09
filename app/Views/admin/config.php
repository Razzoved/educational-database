<?php
    /**
     * View servings as a FORM for admin-settable configuration.
     */
?>

<?= $this->extend('layouts/form') ?>

<?= $this->section('content') ?>
    <div class="page page--centered page--dark">
        <form class="form" method="post" action="<?= url_to('Admin\Config::save') ?>" autocomplete="off">
            <?= csrf_field() ?>

            <!-- DEFAULT IMAGE -->
            <div class="form__group form__group--centered">
                <p class="form__label" style="margin-bottom: 1rem">Default image</p>

                <input type="file"
                    title='Upload image'
                    id="image-uploader"
                    name="image-uploader"
                    accept="image/*"
                    onchange="updateImage(this)"
                    hidden>

                <image id="image"
                    class="form__logo"
                    style="width: 15rem; height: 15rem; object-fit: cover"
                    src="<?= \App\Libraries\Resource::pathToURL($defaultImage) ?>"
                    alt="No image"
                    title="Click to change"
                    onclick="document.getElementById('image-uploader').click()">
                </image>

                <button class="form__button" type="button" title="This action deletes the current image!" onclick="resetImage()">Reset default image</button>
            </div>

            <!-- HOME URL -->
            <fieldset class="form__group">
                <label class="form__label" for="home_url">Home URL</label>
                <input class="form__input"
                    type="url"
                    id="home_url"
                    name="home_url"
                    pattern="https://.*"
                    placeholder="https://example.com"
                    value="<?= $homeURL ?>"
                    onchange="updateHome(this)"
                    required>
            </fieldset>

            <!-- ABOUT URL -->
            <fieldset class="form__group">
                <label class="form__label" for="about_url">About URL</label>
                <input class="form__input"
                    type="url"
                    id="about_url"
                    name="about_url"
                    pattern="https://.*"
                    placeholder="https://example.com"
                    value="<?= $aboutURL ?>"
                    onchange="updateAbout(this)"
                    required>
            </fieldset>

            <!-- actions -->
            <div class="form__group form__group--centered">
                <p class="form__label form__label--small">This page autosaves the changes.</p>
            </div>
        </form>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const ERROR_MODAL = `<?= view('errors/modal', []) ?>`;

    <?php include_once(FCPATH . 'js/fetch.js'); ?>

    const replaceImage = (config) => {
        if (config === undefined) {
            return console.error('Config does not exist');
        }
        document.getElementById('image').src = '<?= base_url() ?>' + config.value + "?t=" + new Date().getTime();
    }

    const updateImage = (selector) => upload(
        '<?= url_to("Admin\Config::save") ?>',
        {
            fileTypeKey: 'type',
            fileType: 'default_image',
            fileKey: 'value',
            selector: selector,
        },
        replaceImage
    );

    const resetImage = () => upload(
        '<?= url_to("Admin\Config::resetImage") ?>',
        {
            fileKey: '',
            selector: { files: [''] },
        },
        replaceImage,
    );

    const updateHome = (element) => updateURL(element,
        (response) => document.getElementById('link-home')?.setAttribute('href', response.value),
    );

    const updateAbout = (element) => updateURL(element,
        (response) => document.getElementById('link-about')?.setAttribute('href', response.value),
    );

    const updateURL = (element, callback) => {
        if (element === undefined || callback === undefined) {
            return console.debug('UploadURL: missing parameters');
        }
        if (element.value === "" || !element.value.match('^' + element.pattern)) {
            return console.debug('Invalid url');
        }

        body = new FormData();
        body.append('type', element.name);
        body.append('value', element.value);

        processedFetch('<?= url_to('Admin\Config::save') ?>', { method: 'POST', body }, response => {
            element.value = response.value;
            callback(response);
        });
    }
</script>
<?= $this->endSection() ?>
