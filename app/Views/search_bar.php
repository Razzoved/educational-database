<form id="search" autocomplete="false" method="get" action="<?= $action ?? '' ?>">
    <input name="search" value="" placeholder="Enter search value" onblur="sendSearch()"/>
    <button type="button" onclick="sendSearch()">Search</button>
    <button type="button" onclick="selectAll()">All</button>
</form>

<script type="text/javascript">
    let searchForm = document.querySelector('#search');
    let filtersInput = document.querySelectorAll('.filter');

    function sendSearch()
    {
        if (searchForm === undefined) {
            return console.error('Cannot find search form. Searching cannot be done!');
        }

        if (filtersInput !== undefined) {
            filtersInput.forEach(function(filter) {
                if (filter.checked) {
                    let item = document.createElement('input');
                    item.setAttribute('type', 'hidden');
                    item.setAttribute('name', filter.name);
                    item.setAttribute('value', 'on');
                    searchForm.appendChild(item);
                }
            })
        }

        searchForm.submit();
    }
</script>