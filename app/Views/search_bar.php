<form class="search" id="search" autocomplete="off" method="get" action="<?= $action ?? '' ?>">
    <input class="search__bar" name="search" value="" placeholder="Enter search value" oninput="getSuggestions()"/>
    <button class="search__submit fas fa-search" type="button" onclick="submitSearch()"></button>
    <ul class="search__suggestions"></ul>
</form>

<script type="text/javascript">
    let lastSuggest = params && params.has('search') ? params.get('search') : "";

    const searchForm = document.querySelector('#search');
    const searchBar = document.querySelector('#search .search__bar');
    const searchSubmit = document.querySelector('#search .search__submit');
    const suggestionsList = document.querySelector('#search .search__suggestions');
    const filtersInput = document.querySelectorAll('.filter');

    const submitSearch = () => {
        if (searchForm === undefined) {
            return console.error('Cannot find search form. Searching cannot be done!');
        }
        if (typeof appendFilters === 'function') {
            appendFilters(searchForm);
        }
        searchForm.submit();
    }

    // Execute a function when the user presses a enter
    searchBar.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            searchSubmit.click();
        }
    });

    const stripAccents = (value) => {
        return value.normalize("NFD")
        .replace(/\p{Diacritic}/gu, "")
        .toLowerCase();
    }

    // Load all available suggestions
    const suggestions = <?= json_encode($options ?? []) ?>;
    suggestions.forEach(function(value, index, target) {
        target[index] = {
            filter: stripAccents(value),
            value: value,
        };
    });

    const filterArray = (filter, array) => {
        let pushed = 0;
        let filtered = [];
        array.forEach(item => {
            if (!item.filter.includes(filter)) {
                return;
            }
            if (pushed > 4 && Math.random() < 0.9) {
                return;
            }
            filtered.push(item.value);
            pushed++;
        });
        return filtered;
    }

    const debounce = (func, timeout = 300) => {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        }
    }

    const suggest = () => {
        if (searchBar === undefined || suggestionsList === undefined) {
            console.error("Missing searchBar or suggestionsList, suggestion cannot be shown!");
            return;
        }

        if (searchBar.value === lastSuggest) return;
        lastSuggest = searchBar.value;

        while (suggestionsList.firstChild) {
            suggestionsList.lastChild.remove();
        }

        if (searchBar.value === "") return;

        filterArray(
            stripAccents(searchBar.value),
            suggestions
        ).forEach(suggestion => {
            let item = document.createElement('li');
            item.classList = "search__suggestion";
            item.innerHTML = suggestion;
            item.setAttribute('onclick', "useSuggestion(this)");
            suggestionsList.appendChild(item);
        });
    }

    const getSuggestions = debounce(() => suggest());

    const useSuggestion = (suggestionElement) => {
        searchBar.value = suggestionElement.textContent;
        submitSearch();
    }
</script>
