<!-- PROPERTY display as an element of a list -->

<li class="list-group-item">
    <input class="form-check-input me-2" type="checkbox" name="<?= $filter->property_tag ?>[<?= $filter->property_value ?>]" label="checkbox-item">
    <?= $filter->property_value ?>
</li>
