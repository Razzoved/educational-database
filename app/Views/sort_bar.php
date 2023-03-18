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
    if (typeof lastSearch === 'undefined') {
        console.error('Missing lastSearch data, sorters will not work as intended!');
    }

    function toggleSort(element)
    {
        let sort = document.createElement('input');
        sort.type = 'hidden';
        sort.name = 'sort';
        sort.value = element.value;

        let sortDir = document.createElement('input');
        sortDir.type = 'hidden';
        sortDir.name = 'sortDir';
        sortDir.value = lastSearch['sort'] === element.value && lastSearch['sortDir'] === 'ASC' ? 'DESC' : 'ASC';

        let form = document.querySelector('form');
        form.action = "";
        form.appendChild(sort);
        form.appendChild(sortDir);
        form.submit();
    }

    document.addEventListener("DOMContentLoaded", function() {
        let sortButton = document.querySelector(`.sort > button[value='${lastSearch['sort']}']`);
        if (!sortButton) {
            console.error(`No sort button was found for: ${lastSearch['sort']}`);
            return;
        }
        sortButton.classList.add('active');

        let sortIcon = sortButton.querySelector(`i`);
        if (!sortIcon || lastSearch['sortDir'] === 'ASC') {
            return;
        }
        sortIcon.classList.add('down');
    });
</script>
