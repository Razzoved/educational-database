<div class="sorters">
    <?php
        foreach ($sorters as $sorter) {
            echo '<button type="button" onclick="toggleSort(this)" value="' . esc(strtolower($sorter)) . '">';
            echo '<i class="fa-solid fa-caret-up"></i>';
            echo esc($sorter);
            echo '</button>';
        }
    ?>
</div>

<script type="text/javascript">
    if (typeof lastSearch === 'undefined') {
        console.error('Missing lastSearch data, sorters will always default to ASC');
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
</script>
