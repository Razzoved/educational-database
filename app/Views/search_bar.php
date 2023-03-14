<form id="search" autocomplete="false" method="post" action="<?= $action ?? '' ?>">
    <input name="search" value="" placeholder="Enter search value"/>
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
                    searchForm.appendChild(filter);
                }
            })
        }

        $.ajax({
            type: "POST",
            URL: searchForm.action,
            data: $('#searchForm').serialize(),
            success: function(result) {
                document.documentElement.innerHTML = result;
            },
            error: function() {
                window.location.reload();
            }
        });
    }
</script>
