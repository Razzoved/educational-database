<!-- automatic filter call on all materials when clicked -->
<form method="post" action='/'>
    <input class="btn btn-fluid btn-outline-secondary w-100"
            type="submit"
            name="filters[<?= esc($tag) ?>][<?= esc($value) ?>]" value="<?= esc($value) ?>"
            style="white-space:normal; word-wrap: normal">
</form>
