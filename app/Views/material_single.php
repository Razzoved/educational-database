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

<div class="page">
    <div class="page__sidebar">
        <?= view('sidebar_buttons', ['properties' => $material->getGroupedProperties()]) ?>
    </div>

    <div class="page__content">
        <article class="material">
            <!-- Header -->
            <header class="material__header">
                <div class="material__row">
                    <img class="material__thumbnail"
                        src="<?= $material->getThumbnail()->getURL() ?>"
                        alt="Material image"
                        <?=isset($material->referTo)
                            ? 'onclick=\'window.location.href="'. esc($material->referTo) . '"\''
                            : '' ?>>
                    <h1 class="material__title">
                        <button class="material__go-back" type='button' onclick="window.history.back()">
                            Go back
                        </button>
                        <?= $material->title ?>
                    </h1>
                </div>
                <div class="material__row">
                    <small>Published: <?= $material->publishedToDate() ?></small>
                    <?= view('rating', ['material' => $material]) ?>
                </div>
            </header>

            <!-- Content -->
            <?php if ($material->content != "") : ?>
            <section class="material__content">
                <?= $material->content ?>
            </section>
            <?php endif; ?>

            <!-- Links -->
            <?php if ($material->getLinks() !== []) : ?>
            <section class="material__attachments">
                <?= view('components/resources_as_links', [
                    'resources' => $material->getLinks(),
                    'title' => 'Attached links'
                ]) ?>
            </section>
            <?php endif; ?>

            <!-- Downloadable -->
            <?php if ($material->getFiles() !== []) : ?>
            <section class="material__attachments">
                <?= view('components/resources_as_links', [
                    'resources' => $material->getFiles(),
                    'title' => 'Downloadable files'
                ]) ?>
            </section>
            <?php endif; ?>

            <!-- Related -->
            <?php if ($material->related !== []) : ?>
            <section class="material__attachments">
                <?= view('components/materials_as_relations', [
                    'materials' => $material->related,
                    'title' => 'Related materials'
                ]) ?>
            </section>
            <?php endif; ?>
        </article>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
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
                ratings.querySelector('.rating__count-value').innerHTML = result.count + 'x';
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
        Array.from(ratings.querySelectorAll('.rating__stars > i')).forEach(c => {
            index++ <= value
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
        Array.from(ratings.querySelectorAll('.rating__stars > i')).forEach(c => {
            c.setAttribute('onmouseover', `rating_reload_class('${index}', 'hover')`);
            c.setAttribute('onmouseout', `rating_reload_class(0, 'hover')`);
            c.setAttribute('onclick', `rating_rate('<?= $material->id ?>', ${index})`);
            rating_reload_class(<?= $rating ?>, 'own');
            index++;
        });
    });
</script>
<?= $this->endSection() ?>
