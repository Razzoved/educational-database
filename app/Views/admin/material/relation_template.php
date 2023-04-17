<div id="relation-<?= $id ?? "template"?>" <?php if (isset($hidden) === false || $hidden === true) echo 'hidden'?>>
    <a href="<?= url_to('Material::get', $id) ?>"><?= $value ?></a>
    <input name="relations[<?= $id ?? '0' ?>]"
        type="hidden"
        value="<?= esc($value ?? 'TEMPLATE') ?>"
        <?php if (isset($readonly) === false || $readonly === true) echo 'readonly' ?>
        <?php if (isset($hidden) === false || $hidden === true) echo 'disabled' ?>
        required>
    <button type="button" <?php if (isset($id)) echo "onclick=\"removeById('relation-$id')\"" ?> style="border: none; color: black; background-color: unset">
        &#10005
    </button>
</div>
