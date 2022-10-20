<!-- PROPERTY displayed as a list of items of same type -->

<?php foreach($filter as $f) : ?>
    <li class="list-group-item">
        <input class="form-check-input me-2" type="checkbox" value=<?= $f['property_value'] ?> aria-label="...">
    </li>
<?php endforeach; ?>