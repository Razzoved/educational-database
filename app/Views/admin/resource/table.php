<?php
    /**
     * Administration panel for managing unused resources.
     *
     * @var string $title    Page header, required.
     * @var array $resources collection of App\Entities\Resource objects
     * @var array $targets   collection of App\Entities\Material objects
     */
?>

<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page">
    <h1 class="page__title"><?= $title ?></h1>

    <div class="page__content">

        <?php if (!empty($resources)) : ?>
        <div class="page__controls">
            <button type="button" onclick="deleteOpenAll('<?= url_to('Admin\Resource::deleteUnusedAll') ?>')">Delete all</button>
        </div>
        <?php endif; ?>

        <div class="table" id="items">
        <?php
            if ($resources === []) {
                echo $this->include('none');
            } else foreach($resources as $resource) {
                echo view('admin/resource/item', [
                    'id'   => $resource->path,
                    'path' => \App\Libraries\Resource::pathToFileURL($resource->getRootPath()),
                    'name' => basename($resource->path),
                ]);
            }
        ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('admin/delete', ['action' => url_to('Admin\Resource::deleteUnused', '@segment@')]) ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript">
    <?php include_once(FCPATH . 'js/modal.js') ?>

    const itemTemplate = `<?= json_encode(view('admin/resource/item')) ?>`;
    const formTemplate = `<?= json_encode(view('admin/resource/form', ['targets' => $targets])) ?>`;

    const items = document.getElementById('items');

    const resourceOpen = async (tmpPath) => {
        if (!tmpPath) {
            console.error('Resource path must be provided');
        } else {
            modalOpen(undefined, formTemplate.fill({ tmp_path: tmpPath }));
        }
    }
</script>
<?= $this->endSection() ?>
