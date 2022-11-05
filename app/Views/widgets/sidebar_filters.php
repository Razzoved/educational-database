<?= $this->extend('layouts/sidebar_list') ?>

<form method="post" action="/">

<?= $this->section('sidebar') ?>
    <?= view('component_extensions/collapsible_property_box', ['properties' => $properties]) ?>
<?= $this->endSection() ?>

</form>
