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

<?= $this->section('sidebar') ?>
<?= view('property/filter_checkbox', ['properties' => $filters]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page__controls">
    <?= view('search_bar', ['url' => url_to('Admin\Property::suggest')]) ?>
    <?= view('sort_bar', ['sorters' => ['Id', 'Category', 'Value', 'Priority', 'Usage'], 'create' => 'propertyOpen()']) ?>
</div>

<div class="table" id="items">
    <?= $this->include('none') ?>
    <?php foreach ($properties as $property) {
        echo view('admin/property/item', [
            'id'          => $property->id,
            'value'       => $property->value,
            'category'    => $property->category ?? "",
            'usage'       => $property->usage,
            'description' => $property->description ?? "",
        ]);
    } ?>
</div>

<?= $pager->links('default', 'full') ?>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('admin/delete', ['action' => url_to('Admin\Property::delete', 0)]) ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    <?php include_once(FCPATH . 'js/fetch.js'); ?>
    <?php include_once(FCPATH . 'js/modal.js') ?>

    const itemTemplate = `<?= json_encode(view('admin/property/item')) ?>`;
    const formTemplate = `<?= json_encode(view('admin/property/form', ['title' => null, 'submit' => null])) ?>`;

    const url = '<?= url_to('Admin\Property::get', 0) ?>';
    const items = document.getElementById('items');

    const propertyOpen = async (id = undefined) => {
        const template = formTemplate.fill(id ? {
            title: 'Update tag',
            submit: 'Update'
        } : {
            title: 'New tag',
            submit: 'Create',
            id: "",
            tag: "",
            value: "",
            category: "",
            description: "",
            priority: 0,
        });
        modalOpen(id ? url.replace(/0$/, id) : undefined, template);
    }
</script>
<?= $this->endSection() ?>