const toggleCollapsible = (element) => {
    let parent = element.closest('.collapsible');
    if (!parent) {
        console.error('Group is invalid, no collapsible parent found.', element);
        return;
    }
    parent.classList.toggle('collapsible--collapsed');
}

const toggleOverflow = (element) => {
    let parent = element.closest('.collapsible');
    if (!parent) {
        console.error('Group is invalid, no collapsible parent found.', element);
        return;
    }
    element.innerHTML = element.innerHTML.indexOf("more") !== -1
        ? element.innerHTML.replace('more', 'less')
        : element.innerHTML.replace('less', 'more');
    parent.classList.toggle('collapsible--no-overflow');
}

const toggleGroup = (element) => {
    let parent = element.closest('.page__group');
    if (!parent) {
        console.error('Group is invalid, no group parent found.', element);
        return;
    }
    parent.classList.toggle('page__group--show');
}

const resetFilters = () => {
    document.querySelectorAll('.filter__checkbox').forEach(e => e.checked=false);
    document.querySelectorAll('.search__bar').forEach(e => e.value='');

    if (typeof submitSearch === "function") {
        submitSearch();
    } else {
        console.warn('All filters were reset, but autorefresh not applied.')
    }
}

const appendFilter = (form, name, value) => {
    const item = document.createElement('input');
    item.setAttribute('type', 'hidden');
    item.setAttribute('name', name);
    item.setAttribute('value', value);
    form.appendChild(item);
}

const appendFilters = (form) => {
    const filters = document.querySelectorAll('.collapsible__toggle-group:checked, .filter__checkbox:checked');
    filters.forEach(filter => {
        const isGroup = filter.classList.contains('collapsible__toggle-group');
        let parent = isGroup
            ? filter.closest('.collapsible')
            : filter;
        while ((parent = parent.parentElement?.closest('.collapsible'))) {
            if (parent.querySelector(':scope > .collapsible__header .collapsible__toggle-group:checked')) {
                return;
            }
        }
        if (isGroup) {
            filter.closest('.collapsible')?.querySelectorAll('.filter__checkbox')?.forEach(f => {
                appendFilter(form, 'group', f.value);
            });
        }
        appendFilter(form, 'filter', filter.value);
    });
}

document.addEventListener("DOMContentLoaded", () => {
    /**
     * Recursively goes through last post and fills all filters
     * into the elements.
     *
     * @param {string} key full name for the form (appends last key)
     * @param {string} value string OR an array to be searched
     */
    const reapplyFilter = (key, value) => {
        var elem = document.querySelector(`input[name='${key}'][value='${value}']`);
        if (!elem) {
            console.debug(`Filter not found: filter_${value}!`)
            return;
        }
        switch (elem.type) {
            case 'checkbox': elem.checked = true; break;
            case 'text': elem.value = value; break;
            default: break;
        }
    }

    const params = new URL(window.location).searchParams;


    params.forEach((val, key) => {
        if (val && !(
            key === 'sort' ||
            key === 'sortDir'
        )) {
            reapplyFilter(key, val)
        }
    });
});
