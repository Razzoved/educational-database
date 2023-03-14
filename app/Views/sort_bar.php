<div class="sorters">
    <?php
        foreach ($sorters as $sorter) {
            echo '<button type="button" onclick="toggleSort(this)" value="' . strtolower(str_replace(' ', '_', esc($sorter))) . '">';
            echo '<i class="fa-solid fa-caret-up"></i>';
            echo esc($sorter);
            echo '</button>';
        }
        if (isset($create)) {
            echo '<button class="create" type="button" onclick="';
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
        let sorter = document.querySelector(`.sorters > button[value='${lastSearch['sort']}']`);
        if (sorter) {
            sorter.classList.add('active');
            if (lastSearch['sortDir'] === 'DESC') sorter.classList.add('down');
        }
    });
</script>
