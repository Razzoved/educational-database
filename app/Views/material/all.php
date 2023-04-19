<?= $this->extend('layouts/main') ?>

<?= $this->section('sidebar') ?>
<?= view('property/filter_checkbox', ['properties' => $filters]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page__controls">
    <?= view('search_bar', ['options' => $options]) ?>
</div>
<div id="items">
    <?php if (empty($materials)) {
        echo '<hr style="margin-top: 1rem; margin-bottom: 1rem">';
        echo '<h2 style="text-align:center">None were found.</h2>';
    } else foreach($materials as $material) {
        echo view('material/item', ['material' => $material]);
    } ?>
</div>
<?php if (isset($pager)) echo $pager->links('default', 'full') ?>
<?= $this->endSection() ?>