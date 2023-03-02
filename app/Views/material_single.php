<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- START OF PAGE SPLIT --->
<div class="page">

    <!-- All tags -->
    <div class="page-sidebar">
        <?= view('sidebar_buttons', ['properties' => $material->getGroupedProperties()]) ?>
    </div>

    <!-- Post container -->
    <div class="page-content">

        <div class="material">
            <div class="material-thumbnail">
                <?= isset($material->referTo) ? "<a href='$material->referTo'>" : "" ?>
                <img src="<?= $material->getThumbnail()->getPath() ?>" alt="Material image">
                <?= isset($material->referTo) ? "</a>" : "" ?>
            </div>

            <header class="material-header">
                <div class="material-title">
                    <button type='button' onclick="history.go(-1)" class="btn btn-dark">
                        Go back
                    </button>
                    <h1><?= $material->title ?></h1>
                </div>
                <div class="material-details">
                    <small><?= $material->createdToDate() ?></small>
                    <?= view('rating', ['material' => $material]) ?>
                </div>
            </header>
        </div>

        <!-- Content -->
        <div class="material-content">
            <?= $material->content ?>

            <div class="material-attachments">
                <!-- Links -->
                <?= view_cell('App\Libraries\Material::listLinks', ['material' => $material]) ?>
                <!-- Downloadable -->
                <?= view_cell('App\Libraries\Material::listFiles', ['material' => $material]) ?>
                <!-- Related -->
                <?= view_cell('App\Libraries\Material::listRelated', ['material' => $material]) ?>
            </div>
        </div>
    </div>
</div>
<!-- END OF PAGE SPLIT -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript">
    function rating_rate(element) {

    }

    function rating_mouseover(element) {

    }
</script>
<?= $this->endSection() ?>
