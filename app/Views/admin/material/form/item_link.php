<div class="form__group form__group--horizontal" id="link-<?= $id ?? "template"?>" <?php if (isset($hidden) === false || $hidden === true) echo 'hidden'?>>
    <input name="links[]"
           type="url"
           class="form__input"
           pattern="https://.*"
           placeholder="https://example.com"
           value="<?= $value ?? 'TEMPLATE' ?>"
           <?php if (isset($readonly) === false || $readonly === true) echo 'readonly' ?>
           <?php if (isset($hidden) === false || $hidden === true) echo 'disabled' ?>
           required>
    <button class="form__input" type="button" <?php if (isset($id)) echo "onclick=\"removeLink('link-$id')\"" ?>>
        &#10005
    </button>
</div>
