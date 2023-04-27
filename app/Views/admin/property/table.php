<?= $this->extend('layouts/admin') ?>

<?= $this->section('header') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page">
    <h1 class="page__title"><?= $title ?></h1>

    <div class="page__sidebar">
        <?= view('property/filter_checkbox', ['properties' => $filters]) ?>
    </div>

    <div class="page__content">
        <div class="page-controls">
            <?= view('search_bar', ['action' => url_to('Admin\Property::index'), 'options' => $options]) ?>
            <?= view('sort_bar', ['sorters' => ['Id', 'Category', 'Value'], 'create' => 'propertyOpen()']) ?>
        </div>

        <div class="table" id="items">
        <?php
            if ($properties === []) {
                echo $this->include('none');
            } else foreach($properties as $property) {
                echo view('admin/property/item', [
                    'id'          => $property->id,
                    'title'       => $property->value,
                    'tag'         => $property->category ?? "",
                    'usage'       => $property->usage,
                    'description' => $property->description
                ]);
            }
            ?>
        </div>

        <?= $pager->links('default', 'full') ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('admin/delete', ['action' => url_to('Admin\Property::delete', 0)]) ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const template = `<?= view('admin/property/item') ?>`
</script>
<?= $this->endSection() ?>
