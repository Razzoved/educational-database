<?= $this->extend('layouts/admin') ?>

<?= $this->section('header') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page">

    <div class="page__sidebar">
        <h1 class="page__title"><?= $title ?></h1>
        <?= view('sidebar_checkboxes', ['properties' => $filters]) ?>
    </div>

    <div class="page__content">
        <h1 class="page__title"><?= $title ?></h1>

        <div class="page-controls">
            <?= view('search_bar', ['action' => url_to('Admin\Property::index'), 'options' => $options]) ?>
            <?= view('sort_bar', ['sorters' => ['Id', 'Tag', 'Value'], 'create' => 'propertyOpen()']) ?>
        </div>

        <div class="table" id="items">
        <?php
            if ($properties === []) {
                echo '<hr style="margin-top: 1rem; margin-bottom: 1rem">';
                echo '<h2 style="text-align:center">None were found.</h2>';
            } else foreach($properties as $property) {
                echo view_cell('\App\Libraries\Property::toRow', [
                    'id'          => $property->id,
                    'title'       => $property->value,
                    'tag'         => $property->category,
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
    const template = `<?= view('./item') ?>`

    const propertyOpen = (id = undefined) => {
        url = id === undefined
            ? '<?= url_to('Admin\Property::create') ?>'
            : '<?= url_to('Admin\Property::get', 0) ?>'.replace(/[0-9]+$/, id);
        modalOpen(url);
    }

    const appendData = (data) => {
    }
</script>
<?= $this->endSection() ?>
