<?= $this->extend('layouts/collapsible_list') ?>

<?= $this->section('item') ?>
    <?= view_cell('\App\Libraries\Property::checkboxList', $properties) ?>
<?= $this->endSection() ?>
