<?php
    /**
     * Template for displaying material as a row in administration.
     * May be deleted by a javascript function deleteId!
     *
     * @var \App\Entities\Material $material object of material entity class
     */

    use App\Entities\Cast\StatusCast;

    $class = 'item' . ($material->status === StatusCast::PUBLIC ? '' : ' private');
    $view = base_url('single/' . $material->id);
    $edit = base_url('admin/materials/edit/' . $material->id);
?>
<article id="<?= $material->id ?>" class="<?= $class ?>">
    <div class="item__header">
        <p class="item__text">
            <small>ID:</small><br>
            <strong><?= $material->id ?></strong>
        </p>
        <img class="item__logo"
            src="<?= $material->getThumbnail()->getURL() ?>"
            alt="missing_img">
        <div class="item__controls">
            <a class="item__preview" target="_self" rel="noreferrer" href="<?= $view ?>">
                View
            </a>
            <a class="item__edit" href="<?= $edit ?>">
                Edit
            </a>
            <button class="item__delete" type="button" onclick="deleteOpen(<?= $material->id ?>)">
                &#10005;
            </button>
        </div>
    </div>
    <section>
        <h2 class="item__title"><?= $material->title?></h2>
        <div class="item__row">
            <p class="item__text">
                <small>Created at:</small><br><?= $material->createdToDate() ?>
            </p>
            <p class="item__text">
                <small>Last update:</small><br><?= $material->updatedToDate() ?>
            </p>
        </div>
        <div class="item__row">
            <p class="item__text">
                <small>Views:</small><br><?= $material->views ?>
            </p>
            <p class="item__text">
                <small>Rating:</small><br><?= $material->rating ?>
            </p>
        </div>
    </section>
</article>
