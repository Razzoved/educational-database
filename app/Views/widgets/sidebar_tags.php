<?= $this->extend('layouts/sidebar_list') ?>

<?= $this->section('sidebar') ?>
    <?= view('component_extensions/collapsible_property_btn', ['properties' => $properties]) ?>
<?= $this->endSection() ?>
