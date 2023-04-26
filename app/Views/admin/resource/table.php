<?php
    /**
     * Administration panel for managing unused resources.
     *
     * @var string $title    Page header, required.
     * @var array $resources collection of App\Entities\Resource objects
     */
?>

<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page">
    <h1 class="page__title"><?= $title ?></h1>

    <div class="page__content">
        <div class="table" id="items">
        <?php
            if ($resources === []) {
                echo '<hr style="margin-top: 1rem; margin-bottom: 1rem">';
                echo '<h2 style="text-align:center">None were found.</h2>';
            } else foreach($resources as $resource) {
                echo view('admin/resource/item', [
                    'id'   => $resource->id,
                    'path' => \App\Libraries\Resources::pathToFileURL($resource->getRootPath()),
                    'name' => basename($resource->path),
                ]);
            }
        ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('admin/delete', ['action' => url_to('Admin\Resource::delete', 0)]) ?>
<?= $this->endSection() ?>
