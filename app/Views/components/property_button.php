<!-- automatic filter call on all materials when clicked -->
<form method="post" action='<?= base_url('/1') ?>' style="display: block">
    <input type="submit"
           name="filters[<?= esc($tag) ?>][<?= esc($value) ?>]" value="<?= esc($value) ?>"
           style="white-space:normal; word-wrap: normal"
    >
</form>
