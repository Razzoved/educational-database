<!-- PROPERTY displayed as a list of items of same type -->

<?php foreach($filter as $f) : ?>
    <li class="list-group-item">
        <input class="form-check-input me-2" type="checkbox" name="<?= $title ?>/<?= $f['property_value'] ?>" label="<?= $f['property_value'] ?>">
        <?= $f['property_value'] ?>
    </li>
<?php endforeach; ?>