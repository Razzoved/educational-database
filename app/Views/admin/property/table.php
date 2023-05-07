<?php
    /**
     * Administration panel for managing properties (tags and categories).
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

    <div class="page__sidebar">
        <?= view('property/filter_checkbox', ['properties' => $filters]) ?>
    </div>

    <div class="page__content">
        <div class="page__controls">
            <?= view('search_bar', ['action' => url_to('Admin\Property::index'), 'options' => $options]) ?>
            <?= view('sort_bar', ['sorters' => ['Id', 'Category', 'Value', 'Priority', 'Usage'], 'create' => 'propertyOpen()']) ?>
        </div>

        <div class="table" id="items">
        <?php
            if ($properties === []) {
                echo $this->include('none');
            } else foreach($properties as $property) {
                echo view('admin/property/item', [
                    'id'          => $property->id,
                    'value'       => $property->value,
                    'tag'         => $property->category ?? "",
                    'usage'       => $property->usage,
                    'description' => $property->description ?? "",
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
    <?php include_once(FCPATH . 'js/modal.js') ?>

    const itemTemplate = `<?= json_encode(view('admin/property/item')) ?>`;
    const formTemplate = `<?= json_encode(view('admin/property/form', ['title' => null, 'submit' => null])) ?>`;

    const url = '<?= url_to('Admin\Property::get', 0) ?>';
    const items = document.getElementById('items');

    const propertyOpen = async (id = undefined) => {
        const template = formTemplate.fill(id
            ? { title: 'Update tag', submit: 'Update' }
            : {
                title: 'New tag',
                submit: 'Create',
                id: "",
                tag: "",
                value: "",
                category: "",
                description: "",
                priority: 0,
            }
        );
        modalOpen(id ? url.replace(/0$/, id) : undefined, template);
    }
</script>
<?= $this->endSection() ?>
