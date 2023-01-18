<?php $pager->setSurroundCount(2) ?>

<div style="width: fit-content; margin: auto">
    <nav aria-label="Page navigation">
        <ul class="pagination btn-group">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <button type="button" class="page-link" onclick="redirectTo('<?= $pager->getFirst() ?>')" aria-label="<?= lang('Pager.first') ?>">
                    <span aria-hidden="true"><?= lang('Pager.first') ?></span>
                </button>
            </li>
        <?php endif ?>

        <?php if ($pager->hasPreviousPage()) : ?>
            <li class="page-item">
                <button type="button" class="page-link" onclick="redirectTo('<?= $pager->getPreviousPage() ?>')" aria-label="<?= lang('Pager.previous') ?>">
                    <span aria-hidden="true">&larr;</span>
                </button>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link): ?>
            <li class="page-item<?= $link['active'] ? ' active' : '' ?>">
                <button type="button" class="page-link" onclick="redirectTo('<?= $link['uri'] ?>')">
                    <?= $link['title'] ?>
                </button>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNextPage()) : ?>
            <li class="page-item">
                <button type="button" class="page-link" onclick="redirectTo('<?= $pager->getNextPage() ?>')" aria-label="<?= lang('Pager.next') ?>">
                    <span aria-hidden="true">&rarr;</span>
                </button>
            </li>
        <?php endif ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <button type="button" class="page-link" onclick="redirectTo('<?= $pager->getLast() ?>')" aria-label="<?= lang('Pager.last') ?>">
                    <span aria-hidden="true"><?= lang('Pager.last') ?></span>
                </button>
            </li>
        <?php endif ?>
        </ul>
    </nav>

    <script type="text/javascript">
        if (typeof lastPost === 'undefined') {
            console.error('lastPost is undefined, PAGER will not work properly');
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

            for (var key in lastPost) {
                addToForm(form, key, lastPost[key]);
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
</div>
