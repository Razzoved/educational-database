<?php
    /**
     * Partial view that generates link input form.
     * It requires dynamics javascript files to be loaded.
     * Bootstrap should be loaded too.
     *
     * Expects:
     * @param links links that already exist
     */
?>

<div class="form__group">

    <!-- file uploader -->
    <div class="form__group form__group--horizontal">
        <input id="link-uploader"
            type="url"
            class="form__input"
            pattern="(http|https)://.*"
            placeholder="https://example.com"
            onblur="verifyLink()">
        <button class="form__input" type="button" onclick="newLink()">Add</button>
    </div>

    <!-- hidden template for js copying -->
    <?= view('admin/material/link_template', ['id' => null, 'value' => null, 'hidden' => true, 'readonly' => true]) ?>

    <div class="form__group" id="link-group">
    <?php
        $index = 0;
        foreach ($links as $link) {
            echo view('admin/material/link_template', ['id' => $index, 'value' => $link, 'hidden' => false, 'readonly' => true]);
            $index++;
        }
    ?>
    </div>
</div>

<script>
    function newLink()
    {
        let uploader = document.getElementById('link-uploader');

        if (!verifyLink()) {
            uploader.classList.add('border-danger');
            return;
        }

        let container = document.getElementById('link-group');
        let link = createLink(
            `link-${parseInt(container.lastElementChild?.id.replace(/^\D+/g, '') ?? '0') + 1}`,
            uploader.value
        );

        container.appendChild(link);
        uploader.value = "";
    }

    function createLink(id, value) {
        let newDiv = document.getElementById("link-template").cloneNode(true);
        let input = newDiv.firstElementChild;
        let button = newDiv.lastElementChild;

        if (input === undefined || button === undefined) console.warn("invalid link template: undefined")
        if (input === button) console.warn("invalid link template: input=button")

        input.disabled = null;
        input.required = true;
        input.setAttribute('value', value);

        button.onclick = () => removeById(id);

        newDiv.id = id;
        newDiv.hidden = false;

        return newDiv;
    }

    function removeLink(id)
    {
        let element = document.getElementById(id);
        let path = element.querySelector('input').value;

        if (typeof addToUnused === 'function' && path !== undefined && path !== '') {
            addToUnused(path);
        }
        element.remove();
    }

    function verifyLink() {
        // check for pattern
        let uploader = document.getElementById('link-uploader');
        if (uploader.value !== "" && !uploader.value.match('^' + uploader.pattern)) {
            uploader.classList.add('border-danger');
            return false;
        }
        // check for duplicates
        let linkGroup = document.getElementById('link-group').querySelectorAll('input');
        for (var key in linkGroup) {
            if (uploader.value === linkGroup[key].value) {
                uploader.classList.add('border-danger');
                return false;
            }
        }
        // its ok
        uploader.classList.remove('border-danger');
        return uploader.value !== "";
    }
</script>
