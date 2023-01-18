<div id="link-<?= $id ?? "template"?>" class="row g-0 edit-mt" <?php if (isset($hidden) === false || $hidden === true) echo 'hidden'?>>
    <input name="links[]"
           type="url"
           class="form-control col"
           pattern="https://.*"
           placeholder="https://example.com"
           value="<?= $value ?? 'TEMPLATE' ?>"
           <?php if (isset($readonly) === false || $readonly === true) echo 'readonly' ?>
           <?php if (isset($hidden) === false || $hidden === true) echo 'disabled' ?>
           required>
    <button type="button" <?php if (isset($id)) echo "onclick=\"removeById('link-$id')\"" ?> class="btn col-auto" style="font-weight: bold;">&#10005</button>
</div>
