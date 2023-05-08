<?php
    /**
     * Partial view that generates link input form.
     * It requires dynamics javascript files to be loaded.
     * Bootstrap should be loaded too.
     *
     * Expects:
     * @param array $links An array of links
     */
?>

<div class="form__group">

    <!-- file uploader -->
    <div class="form__group form__group--horizontal-flex">
        <input id="link-uploader"
            type="url"
            class="form__input"
            pattern="(http|https)://.*"
            placeholder="https://example.com"
            onblur="uniqueLink()">
        <button class="form__button" type="button" onclick="newLink()">Add</button>
    </div>

    <div class="form__group" id="link-group">
        <?php foreach ($links as $link) {
            echo view('admin/material/form/item_link', [
                'id' => $link->id,
                'path' => $link->getURL()
            ]);
        } ?>
    </div>
</div>

<script>
    const linkUploader = document.getElementById('link-uploader');
    const linkGroup = document.getElementById('link-group');

    const uniqueLink = () => {
        // check for pattern
        if (linkUploader.value !== "" && !linkUploader.value.match('^' + linkUploader.pattern)) {
            return linkUploader.setInvalid(true);
        }
        // check for duplicates
        const links = linkGroup.querySelectorAll('input[type="url"]');
        for (var key in links) {
            if (linkUploader.value === links[key].value) {
                return linkUploader.setInvalid(true);
            }
        }
        return linkUploader.setInvalid(false) && linkUploader.value !== "";
    }

    const newLink = () => {
        if (!uniqueLink()) {
            linkUploader.focus();
            return;
        }
        // create a new link
        const template = `<?= view('admin/material/form/item_link') ?>`
            .replace(/@id@/g, crypto.randomUUID())
            .replace(/@path@/g, linkUploader.value);
        linkUploader.value = "";
        // add to document
        linkGroup.insertAdjacentHTML('beforeend', template);
    }

    const removeLink = (id) => {
        let element = document.getElementById(id);
        let path = element.querySelector('input[type="url"]')?.value;
        if (typeof addToUnused === 'function' && path !== undefined && path !== '') {
            addToUnused(path);
        }
        element.remove();
    }
</script>
