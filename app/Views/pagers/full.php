<?php $pager->setSurroundCount(2) ?>

<div class="pagination">
    <nav aria-label="Page navigation">
        <ul class="pagination__list">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="pagination__item">
                <a class="pagination__button" href='<?= $pager->getFirst() ?>' aria-label="<?= lang('Pager.first') ?>">
                    <span class="pagination__text" aria-hidden="true"><?= lang('Pager.first') ?></span>
                </a>
            </li>
        <?php endif ?>

        <?php if ($pager->hasPreviousPage()) : ?>
            <li class="pagination__item">
                <a class="pagination__button" href='<?= $pager->getPrevious() ?>' aria-label="<?= lang('Pager.previous') ?>">
                    <span class="pagination__text" aria-hidden="true">&larr;</span>
                </a>
            </li>
        <?php endif ?>

        <?php $pageNum = $pager->getCurrentPageNumber() ?>
        <?php foreach ($pager->links() as $link): ?>
            <?php // hide too many links on small screens
                $hideable = "";
                if ($link['title'] != $pageNum) {
                    $hideable .= ' pagination__item--hideable';
                }
                if ($link['title'] == $pageNum - 1 || $link['title'] == $pageNum + 1) {
                    $hideable .= '-xs';
                }
            ?>
            <li class="pagination__item<?= $link['active'] ? ' active' : '' ?><?= $hideable ?>">
                <a class="pagination__button" href='<?= $link['uri'] ?>'>
                    <span class="pagination__text"><?= $link['title'] ?></span>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNextPage()) : ?>
            <li class="pagination__item">
                <a class="pagination__button" href='<?= $pager->getNextPage() ?>' aria-label="<?= lang('Pager.next') ?>">
                    <span class="pagination__text" aria-hidden="true">&rarr;</span>
                </a>
            </li>
        <?php endif ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="pagination__item">
                <a class="pagination__button" href='<?= $pager->getLast() ?>' aria-label="<?= lang('Pager.last') ?>">
                    <span class="pagination__text" aria-hidden="true"><?= lang('Pager.last') ?></span>
                </a>
            </li>
        <?php endif ?>
        </ul>
    </nav>
</div>
