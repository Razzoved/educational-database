<input class="form-check-input me-2"
        type="checkbox"
        name="filters[<?= esc($tag) ?>][<?= esc($value) ?>]"
        id="filters[<?= esc($tag) ?>][<?= esc($value) ?>]"
        label="checkbox-item">
<label for="filters[<?= esc($tag) ?>][<?= esc($value) ?>]">
    <?= esc($value) ?>
</label>
</input>
