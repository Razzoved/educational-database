<div class="sort">
    <?php
    foreach ($sorters as $sorter) {
        echo '<button class="sort__button" type="button" onclick="toggleSort(this)" value="' . strtolower(str_replace(' ', '_', esc($sorter))) . '">';
        echo '<i class="sort__icon fa-solid fa-caret-up"></i>';
        echo esc($sorter);
        echo '</button>';
    }
    if (isset($create)) {
        echo '<button class="sort__button create" type="button" onclick="';
        echo $create;
        echo '">&#65291</button>';
    }
    ?>
</div>

<script type="text/javascript">
    if (params === undefined) {
        console.error('Query params undefined, sorters will not work as intended!');
    }

    const toggleSort = (element) => {
        let sort = document.createElement('input');
        sort.type = 'hidden';
        sort.name = 'sort';
        sort.value = element.value;

        let sortDir = document.createElement('input');
        sortDir.type = 'hidden';
        sortDir.name = 'sortDir';
        sortDir.value = params.get('sort') === element.value && params.get('sortDir') === 'ASC' ? 'DESC' : 'ASC';

        let form = document.querySelector('form');
        form.action = "";
        form.appendChild(sort);
        form.appendChild(sortDir);
        form.submit();
    }

    // Sets up the active toggler (coloration and direction of arrow).
    document.addEventListener("DOMContentLoaded", () => {
        const sortButton = document.querySelector(`.sort__button[value='${params.get('sort')}']`);
        if (!sortButton) {
            console.error(`No sort button was found for: ${params.get('sort')}`);
            return;
        }
        sortButton.classList.add('active');
        const sortIcon = sortButton.querySelector(`i`);
        if (sortIcon && params.get('sortDir').toUpperCase() === 'DESC') {
            sortIcon.classList.add('down');
        }
    });
</script>