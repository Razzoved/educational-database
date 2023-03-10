<?php
    /**
     * MODAL: Resource assigner.
     *
     * @param string $title Title of modal
     * @param string $submit Custom text for submit button
     *
     * @param array  $targets All possible assignment targets (array of id -> name)
     */
?>
<div class="modal" id="resource-window">

    <div class="modal-content">
        <div class="modal-header">
            <h1><?= $title ?? 'Assign resource' ?></h1>
            <span class="close" id="user-close" onclick="resourceClose()">&#10005</span>
        </div>

        <div class="modal-body">
            <?php $extra = ['class' => 'form-control'] ?>
            <?php helper('form') ?>

            <?= form_open(base_url('admin/files/assign'), ['id' => 'resource-form']) ?>

            <?= form_hidden("tmp_path", '') ?>

            <div class="form-floating">
                <?= form_label('Assign to', 'target') ?>
                <input id="target" name="target"
                        list="target-options"
                        class="form-control col edit-mr"
                        placeholder="No material selected"
                        onblur="verifyTarget()">
                <datalist id="target-options">
                    <?php foreach ($targets as $id => $title) : ?>
                        <option value='<?= esc($title) ?>' data-value='<?= $id ?>'>
                    <?php endforeach; ?>
                </datalist>
            </div>

            <?= form_close() ?>
        </div>

        <div class="modal-footer">
            <div style="display: flex; justify-content: space-between;">
                <button type="button" class="btn btn-primary me-2" style="width: 5rem" onclick="resourceSubmit()"><?= $submit ?? 'Submit' ?></button>
                <button type="button" class="btn btn-danger" style="width: 5rem" onclick="resourceClose()">Cancel</button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        let resourceModal = document.getElementById("resource-window");
        let resourcePath = resourceModal.querySelector(`input[name="tmp_path"]`);
        let resourceTarget = document.querySelector("#target");

        // When the user clicks anywhere outside of the modal, close it
        window.addEventListener("click", function(event) {
            if (event.target === resourceModal) {
                resourceModal.style.display = "none";
            }
        });

        async function resourceClose()
        {
            resourceModal.style.display = "none";
        }

        function resourceOpen(id = undefined)
        {
            let resource = document.getElementById(id);
            let name = resource.querySelector('.name').innerHTML;
            if (resource !== undefined) {
                document.querySelector('#resource-error')?.remove();
                resourcePath.value = resource.id;
                resourceTarget.value = '';
                resourceModal.style.display = "block";
            } else {
                console.debug('ERROR: resource is undefined');
            }
        }

        function resourceSubmit()
        {
            document.querySelector('#resource-error')?.remove();
            let form = $('#resource-form');
            let materialId = document.querySelector("#target-options option[value='" + resourceTarget.value + "']").getAttribute('data-value');
            $.ajax({
                type: 'POST',
                url: form.attr('action') + '/' + materialId,
                dataType: 'json',
                data: form.serialize(),
                success: function(unused) {
                    document.getElementById(resourcePath.value).remove();
                    resourceClose();
                },
                error: (jqXHR) => {
                    showError(jqXHR);
                    resourceClose();
                }
            })
        }

        function verifyTarget()
        {
            let option = document.getElementById('target-options').querySelector(`option[value="${resourceTarget.value.replaceAll('"', '\\"')}"]`);
            if (option === null && resourceTarget.value !== "") {
                resourceTarget.classList.add('border-danger');
                resourceTarget.value = "";
            } else {
                resourceTarget.classList.remove('border-danger');
            }
        }
    </script>
</div>
