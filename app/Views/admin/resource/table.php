<?= $this->extend('layouts/admin') ?>

<?= $this->section('header') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page">

    <main class="page-content">
        <h1><?= $title ?></h1>
        <div class="table" id="items">
        <?php
            foreach($resources as $index => $resource) {
                echo view('components/resource_as_unused', ['resource' => $resource, 'index' => $index, 'showButtons' => true]);
            }
        ?>
        </div>
    </main>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('admin/delete', ['action' => base_url('admin/files/delete'), 'idName' => 'path']) ?>
<?= view('admin/resource/form', ['title' => 'Resource assignment', 'submit' => 'Assign', 'targets' => $targets]) ?>
<?= $this->endSection() ?>
