<?php $pager->setSurroundCount(2) ?>

<div class="pagination">
    <nav aria-label="Page navigation">
        <ul class="pagination__list">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="pagination__item">
                <button class="pagination__button" type="button" onclick="redirectTo('<?= $pager->getFirst() ?>')" aria-label="<?= lang('Pager.first') ?>">
                    <span class="pagination__text" aria-hidden="true"><?= lang('Pager.first') ?></span>
                </button>
            </li>
        <?php endif ?>

        <?php if ($pager->hasPreviousPage()) : ?>
            <li class="pagination__item">
                <button class="pagination__button" type="button" onclick="redirectTo('<?= $pager->getPreviousPage() ?>')" aria-label="<?= lang('Pager.previous') ?>">
                    <span class="pagination__text" aria-hidden="true">&larr;</span>
                </button>
            </li>
        <?php endif ?>

        <?php $pageNum = $pager->getCurrentPageNumber() ?>
        <?php foreach ($pager->links() as $link): ?>
            <?php // hide too many links on small screens
                $hideable = ($link['title'] != $pageNum
                    && $link['title'] != $pageNum - 1
                    && $link['title'] != $pageNum + 1) ? ' pagination__item--hideable' : '';
            ?>
            <li class="pagination__item<?= $link['active'] ? ' active' : '' ?><?= $hideable ?>">
                <button class="pagination__button" type="button" onclick="redirectTo('<?= $link['uri'] ?>')">
                    <span class="pagination__text"><?= $link['title'] ?></span>
                </button>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNextPage()) : ?>
            <li class="pagination__item">
                <button class="pagination__button" type="button" onclick="redirectTo('<?= $pager->getNextPage() ?>')" aria-label="<?= lang('Pager.next') ?>">
                    <span class="pagination__text" aria-hidden="true">&rarr;</span>
                </button>
            </li>
        <?php endif ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="pagination__item">
                <button class="pagination__button" type="button" onclick="redirectTo('<?= $pager->getLast() ?>')" aria-label="<?= lang('Pager.last') ?>">
                    <span class="pagination__text" aria-hidden="true"><?= lang('Pager.last') ?></span>
                </button>
            </li>
        <?php endif ?>
        </ul>
    </nav>
</div>

<script type="text/javascript">
    if (typeof lastSearch === 'undefined') {
        console.error('lastSearch is undefined, PAGER will not work properly');
    }

    /**
     * This function redirects to the next page, but keeps the previously
     * searched results/filters.
     *
     * @param {string} url Target URL to redirect to
     */
    function redirectTo(url)
    {
        let form = document.createElement('form');
        form.action = url;
        form.method = 'post';

        for (var key in lastSearch) {
            addToForm(form, key, lastSearch[key]);
        }

        document.body.appendChild(form);
        form.submit();
    }

    /**
     * Recursively goes through last post and saves all values
     * into a new form to be submitted.
     *
     * @param {HTMLFormElement} form
     * @param {string} key full name for the form (appends last key)
     * @param {string} value string OR an array to be searched
     */
    function addToForm(form, key, value)
    {
        if (typeof value == 'object' ) {
            for (var k in value) {
                addToForm(form, `${key}[${k}]`, value[k]);
            }
        } else {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }
    }
</script>
