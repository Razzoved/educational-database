if (!processedFetch) {
  console.error("AJAX calls will fail, no FETCH.js provided!");
} else {
  console.debug("Loaded search.js");
}

// ---------------------------------------------------------------------------
//                                SEARCHING
// ---------------------------------------------------------------------------

const searchForm = document.getElementById("search");
if (searchForm === undefined) {
  return console.error("Cannot find search form!");
}

const searchBar = searchForm.querySelector(".search__bar");
const searchSubmit = searchForm.querySelector(".search__submit");

if (searchBar === undefined || searchSubmit === undefined) {
  return console.error("Missing search form component!");
}

// Refill search bar with query params
let lastSuggest = params.get("search") ?? "";
searchBar.value = lastSuggest;

// Execute a function when the user presses a enter
searchBar.addEventListener("keypress", function (event) {
  if (event.key === "Enter") {
    event.preventDefault();
    searchForm.querySelector(".search__submit").click();
  }
});

// Execute when user presses submit button
searchSubmit.addEventListener("click", () => {
  if (typeof appendFilters === "function") {
    appendFilters(searchForm);
  }
  searchForm.submit();
});

// ---------------------------------------------------------------------------
//                                SUGGESTING
// ---------------------------------------------------------------------------

if (typeof searchForm.dataset.url === "undefined") {
  return console.error("Missing url for getting search suggestions!");
}

const suggestionsList = searchForm.querySelector(".search__suggestions");
if (suggestionsList === undefined) {
  return console.error("Missing suggestions list component!");
}

const useSuggestion = (suggestionElement) => {
  searchBar.value = suggestionElement.textContent;
  searchSubmit.click();
};

const debounce = (func, timeout = 300) => {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => {
      func.apply(this, args);
    }, timeout);
  };
};

const suggest = () => {
  // ignore same value
  if (searchBar.value === lastSuggest) return;

  lastSuggest = searchBar.value;

  // keep suggestions for empty value
  if (lastSuggest === "") return;

  processedFetch(
    searchForm.dataset.url,
    {
      method: "GET",
      data: lastSuggest,
    },
    (response) => {
      // remove all previous suggestions
      while (suggestionsList.firstChild) {
        suggestionsList.lastChild.remove();
      }
      // add new suggestions
      response.forEach((r) => {
        let item = document.createElement("li");
        item.classList = "search__suggestion";
        item.innerHTML = r;
        item.onclick = `${useSuggestion.name}()`;
        suggestionsList.appendChild(item);
      });
    }
  );
};

// Suggest on search bar change
searchBar.addEventListener("change", () => debounce(suggest));
