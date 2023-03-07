<?php
    /**
     * Template for a single material page.
     *
     * Expects:
     * @param App\Entities\Material $material   material which material to render
     * @param ?App\Entities\Rating  $rating     rating from the current user
     */

    $rating = $rating->rating_value ?? 0;
?>

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script type="text/javascript">
    // Reference to the rating group.
    let ratings = document.querySelector('.rating');

    /**
     * This function handles user interaction with the rating system.
     *
     * @param {string}  materialId  id of material, expected to be convertible to integer
     * @param {int}     rating      value the user wants to rate the post with
     */
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
                rating_reload_class(result.average, 'active');
                rating_reload_class(result.user, 'own');
                ratings.querySelector('.count').innerHTML = result.count + 'x';
            },
            error: function(status) {
                alert('Unexpected error: could not rate the material');
            },
        });
    }

    /**
     * Goes through ratings group. If element is of 'i' tag:
     * - if its index <= value, add className
     * - if its index > value, remove className
     *
     * @param {number} value        used for comparison
     * @param {string} className    added/removed
     */
    function rating_reload_class(value, className) {
        index = 1;
        Array.from(ratings.children).forEach(c => {
            c.nodeName.toLowerCase() === 'i' && index++ <= value
                ? c.classList.add(className)
                : c.classList.remove(className);
        });
    }

    /**
     * Sets the functionality from this file onto the rating group.
     * Each of its child elements gets coloring and interaction.
    */
    window.addEventListener('DOMContentLoaded', (event) => {
        var index = 1;
        Array.from(ratings.children).forEach(c => {
            if (c.nodeName.toLowerCase() === 'i') {
                c.setAttribute('onmouseover', `rating_reload_class('${index}', 'hover')`);
                c.setAttribute('onmouseout', `rating_reload_class(0, 'hover')`);
                c.setAttribute('onclick', `rating_rate('<?= $material->id ?>', ${index})`);
                rating_reload_class(<?= $rating ?>, 'own');
                index++;
            }
        });
    });
</script>
<?= $this->endSection() ?>
