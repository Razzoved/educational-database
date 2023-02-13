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

<div class="dynamic-input">

    <!-- file uploader -->
    <div class="row g-0">
        <input id="link-uploader"
            type="url"
            class="form-control col edit-mr"
            pattern="https://.*"
            placeholder="https://example.com"
            onblur="verifyLink()">
        <button class="btn btn-md btn-secondary col-2" type="button" onclick="newLink()">Add</button>
    </div>

    <!-- hidden template for js copying -->
    <?= view('admin/link_template', ['id' => null, 'value' => null, 'hidden' => true, 'readonly' => true]) ?>

    <div id="link-group">
    <?php
        $index = 0;
        foreach ($links as $link) {
            echo view('admin/link_template', ['id' => $index, 'value' => $link, 'hidden' => false, 'readonly' => true]);
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

    function verifyLink() {
        let uploader = document.getElementById('link-uploader');
        if (uploader.value !== "" && !uploader.value.match('^' + uploader.pattern)) {
            uploader.classList.add('border-danger');
            return false;
        }
        uploader.classList.remove('border-danger');
        return uploader.value !== "";
    }
</script>
