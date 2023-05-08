<?php
    /**
     * View of a single material page.
     *
     * Expects:
     * @param App\Entities\Material $material   material which material to render
     * @param ?App\Entities\Rating  $rating     rating from the current user
     */
    $rating = $rating->rating_value ?? 0;
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('sidebar') ?>
<?php if (isset($material->properties) && !empty($material->properties)) : ?>
    <?= view('property/filter_button', ['properties' => $material->properties]) ?>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<article class="material">
    <!-- Header -->
    <div class="material__header">
        <img class="material__thumbnail"
            src="<?= $material->getThumbnail()->getURL() ?>"
            alt="Material image"
            <?= isset($material->referTo)
                ? 'onclick=\'window.location.href="'. esc($material->referTo) . '"\''
                : '' ?>>
        <div class="material__details">
            <button class="material__go-back" type='button' onclick="window.history.back()">
                Go back
            </button>
            <div class="material__dynamic-row">
                <small>Published: <?= $material->publishedToDate() ?></small>
                <?= view('material/rating', ['material' => $material, 'twoLines' => true]) ?>
            </div>
        </div>
    </div>

    <!-- Content -->
    <?php if ($material->content != "") : ?>
    <section class="material__content">
        <?= $material->content ?>
    </section>
    <?php endif; ?>

    <!-- Links -->
    <?php if ($material->getLinks() !== []) : ?>
    <section class="material__attachments">
        <?= view('resource/link', [
            'resources' => $material->getLinks(),
            'title' => 'Attached links'
        ], [
            'saveData' => false
        ]) ?>
    </section>
    <?php endif; ?>

    <!-- Downloadable -->
    <?php if ($material->getFiles() !== []) : ?>
    <section class="material__attachments">
        <?= view('resource/link', [
            'resources' => $material->getFiles(),
            'title' => 'Downloadable files'
        ], [
            'saveData' => false
        ]) ?>
    </section>
    <?php endif; ?>

    <!-- Related -->
    <?php if ($material->related !== []) : ?>
    <section class="material__attachments">
        <?= view('material/relation', [
            'materials' => $material->related,
            'title' => 'Related materials'
        ], [
            'saveData' => false
        ]) ?>
    </section>
    <?php endif; ?>
</article>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript">
    const ratings = document.querySelector('.rating');

    /**
     * This function handles user interaction with the rating system
     * @param {int}     rating      value the user wants to rate the post with
     */
    const ratingRate = async (rating) => {
        const response = await fetch('<?= url_to('Material::rate', $material->id) ?>', {
            method: 'POST',
            body: new URLSearchParams({'value': rating})
        });

        if (!response.ok) {
            console.error(`error: ${response.statusText}`);
            alert(`Could not rate material: ${response.statusText}`);
            return;
        }

        const data = await response.json();
        console.debug(data);

        ratingReloadClass(data.average, 'active');
        ratingReloadClass(data.user, 'own');
        ratings.querySelector('.rating__count-value').innerHTML = data.count + 'x';
    }

    /**
     * Goes through ratings group. If element is of 'i' tag:
     * - if its index <= value, add className
     * - if its index > value, remove className
     *
     * @param {number} value        used for comparison
     * @param {string} className    added/removed
     */
    const ratingReloadClass = (value, className) => {
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
        Array.from(ratings.querySelectorAll('.rating__stars > i')).forEach((c, index) => {
            c.setAttribute('onmouseover', `ratingReloadClass('${index + 1}', 'hover')`);
            c.setAttribute('onmouseout', `ratingReloadClass(0, 'hover')`);
            c.setAttribute('onclick', `ratingRate(${index + 1})`);
            ratingReloadClass(<?= $rating ?>, 'own');
        });
    });
</script>
<?= $this->endSection() ?>
