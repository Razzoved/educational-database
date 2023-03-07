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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script type="text/javascript">
    function rating_rate(materialId, rating) {
        $.ajax({
            url: '<?= base_url('single/rate') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                id: materialId,
                value: rating,
            },
            success: function(result) {
                var index = 1;
                var ratingsGroup = document.querySelector('.rating');
                Arrays.from(ratingsGroup.children).forEach(c => {
                    if (index <= result.rating) {
                        c.classList.add('active');
                        index += 1;
                    } else {
                        c.classList.remove('active');
                    }
                });
                ratingsGroup.querySelector('average').innerHTML = result.average;
                ratingsGroup.querySelector('count').innerHTML = result.count;
            },
            error: function(status) {
                alert('Unexpected error: could not rate the material');
            },
        });
    }

    function rating_mouseover(element) {
        do {
            element.classList.add('hover');
            element = element.previousElementSibling;
        } while (element);
    }

    function rating_mouseover_reset() {
        Array.from(document.querySelector('.rating').children).forEach(c => {
            c.classList.remove('hover');
        });
    }

    // Add css events to rating icons
    window.addEventListener('DOMContentLoaded', (event) => {
        Array.from(document.querySelector('.rating').children).forEach(c => {
            var index = 1;
            if (c.nodeName.toLowerCase() === 'i') {
                c.setAttribute('onmouseover', 'rating_mouseover(this)');
                c.setAttribute('onmouseout', 'rating_mouseover_reset()');
                c.setAttribute('onclick', `rating_rate('<?= $material->id ?>', ${index})`);
                index = index + 1;
            }
        });
    });
</script>
<?= $this->endSection() ?>
