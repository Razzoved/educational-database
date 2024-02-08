import { debounce } from "./modules/delay.js";
import { processedFetch } from "./modules/fetch.js";

class Search {
  #form;
  #suggestion; #suggestions;

  constructor (form) {
    if (!form) {
      throw new Error("Search form is required!");
    }

    this.#form = form;
    this.input = form.querySelector(".search__bar");
    this.submit = form.querySelector(".search__submit");

    if (!this.input || !this.submit) {
      throw new Error("Missing search form component!");
    }

    this.#suggestions = form.querySelector(".search__suggestions");
    this.#suggestion = params.get("search") ?? "";
    this.input.value = this.#suggestion;

    if (typeof this.#form.dataset.url === "undefined" || !this.#suggestions) {
      return console.warn("Suggestions cannot be displayed!");
    }
  }

  onKeypress(event) {
    if (event.key === "Enter") {
      event.preventDefault();
      this.submit.click();
    }
  }

  onSubmit(event) {
    if (typeof appendFilters === "function") {
      appendFilters(this.#form);
    }
    this.#form.submit();
  }

  enableEnter() {
    this.input.addEventListener("keypress", this.onKeypress.bind(this));
    return this;
  }

  enableSubmit() {
    this.submit.addEventListener("click", this.onSubmit.bind(this));
    return this;
  }

  disableEnter() {
    this.input.removeEventListener("keypress", this.onKeypress.bind(this));
    return this;
  }

  disableSubmit() {
    this.submit.removeEventListener("click", this.onSubmit.bind(this));
    return this;
  }

  enableSuggest() {
    if (!this.#suggestions) {
      console.warn('Suggestion cannot be enabled!');
    } else {
      this.input.addEventListener("input", debounce(this.suggest.bind(this)));
    }
    return this;
  }

  disableSuggest() {
    this.input.removeEventListener("input", debounce(this.suggest.bind(this)));
    return this;
  }

  suggest() {
    // do not update on same value
    if (this.input.value === this.#suggestion) return;

    this.#suggestion = this.input.value;

    // keep last suggestions whenever its empty
    if (this.#suggestion === "") return;

    processedFetch(
      `${this.#form.dataset.url}?${(new URLSearchParams({search: this.#suggestion})).toString()}`,
      { method: "GET" },
      (response) => {
        // remove all previous suggestions
        while (this.#suggestions.firstChild) {
          this.#suggestions.lastChild.remove();
        }
        // add new suggestions
        response.forEach((r) => {
          let elem = document.createElement("li");
          elem.classList = "search__suggestion";
          elem.innerHTML = r;
          elem.addEventListener("click", () => {
            this.input.value = elem.textContent;
            this.submit.click();
          });
          this.#suggestions.appendChild(elem);
        });
      }
    );
  };
}

const search = new Search(document.getElementById("search"))
  .enableEnter()
  .enableSubmit()
  .enableSuggest();

console.debug("Loaded search: ", search);
