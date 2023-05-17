<?php
    /**
     * Template for a single material page.
     *
     * Expects:
     * @param array $filters
     * @param array $options
     * @param array $materials
     * @param mixed $pager
     */
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('sidebar') ?>
<?php if (isset($filters) && !empty($filters)) : ?>
    <?= view('property/filter_checkbox', ['properties' => $filters]) ?>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page__controls">
    <?= view('search_bar', ['options' => $options]) ?>
</div>
<div id="items">
    <?= $this->include('none') ?>
    <?php foreach($materials as $material) {
        echo view('material/item', ['material' => $material]);
    } ?>
</div>
<?php if (isset($pager)) echo $pager->links('default', 'full') ?>
<?= $this->endSection() ?>
