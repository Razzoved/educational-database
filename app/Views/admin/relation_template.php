<div id="relation-<?= $id ?? "template"?>" class="row g-0 edit-mt" <?php if (isset($hidden) === false || $hidden === true) echo 'hidden'?>>
    <a class="form-control col" href="<?= base_url('single/' . $id) ?>"><?= $value ?></a>
    <input name="relations[<?= $id ?? '0' ?>]"
        type="hidden"
        value="<?= esc($value ?? 'TEMPLATE') ?>"
        <?php if (isset($readonly) === false || $readonly === true) echo 'readonly' ?>
        <?php if (isset($hidden) === false || $hidden === true) echo 'disabled' ?>
        required>
    <button type="button" <?php if (isset($id)) echo "onclick=\"removeById('relation-$id')\"" ?> class="btn col-auto" style="font-weight: bold;">&#10005</button>
</div>
