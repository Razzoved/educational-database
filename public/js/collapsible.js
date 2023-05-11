const toggleCollapsible = (element, classItem = 'collapsible--collapsed') => {
    const parent = element.closest('.collapsible');
    if (!parent) {
        console.error('Group is invalid, no collapsible parent found.', element);
        return false;
    }
    parent.classList.toggle(classItem);
    return true;
}

const toggleOverflow = (element) => {
    if (toggleCollapsible(element, 'collapsible--no-overflow')) {
        element.innerHTML = element.innerHTML.indexOf("more") !== -1
            ? element.innerHTML.replace('more', 'less')
            : element.innerHTML.replace('less', 'more');
    }
}

const toggleGroup = (element) => {
    const parent = element.closest('.page__group');
    if (!parent) {
        console.error('Group is invalid, no group parent found.', element);
        return;
    }
    parent.classList.toggle('page__group--show');
}

const saveFilters = () => {
    // save filters to session storage to save query space (GET is limited)
    const toReapply = Array.from(document.querySelectorAll('.collapsible *:checked')).map(item => item.id);
    window.sessionStorage.setItem('reapply-page', window.location.pathname);
    window.sessionStorage.setItem('reapply-filters', JSON.stringify(toReapply));
}

const resetFilters = () => {
    document.querySelectorAll('.filter__checkbox, .collapsible__toggle-group').forEach(e => e.checked=false);
    document.querySelectorAll('.search__bar').forEach(e => e.value='');
    if (typeof submitSearch === "function") {
        submitSearch();
    } else {
        console.warn('All filters were reset, but autorefresh not applied.')
    }
    saveFilters();
}

const appendFilter = (form, name, value) => {
    const item = document.createElement('input');
    item.setAttribute('type', 'hidden');
    item.setAttribute('name', name + '[]');
    item.setAttribute('value', value);
    form.appendChild(item);
}

const appendFilters = (form) => {
    saveFilters();

    // hierarchical filtering
    Array.from(document.querySelectorAll('.collapsible--selected'))
        .filter(item => item.parentElement?.closest('.collapsible--selected') === null)
        .forEach(item => {
            const current = item.querySelector(':scope > .collapsible__header .collapsible__toggle-group');
            const name = `${current.name}[${current.value}]`;
            if (current.value != 0) appendFilter(form, name, current.value);
            const children = item.querySelectorAll(':scope > .collapsible__content .collapsible__toggle-group');
            Array.from(children).forEach(child => appendFilter(form, name, child.value));
        });

    // filtering of tags where no collapsible is selected
    Array.from(document.querySelectorAll('.filter__checkbox:checked'))
        .filter(item => item.closest('.collapsible--selected') === null)
        .forEach(item => appendFilter(form, item.name, item.value));
}

/**
 * Loads applied filters either from the state data or from the sessionStorage.
 * State is always preffered if possible.
 */
document.addEventListener("DOMContentLoaded", () => {
    const reapplyFilter = (id) => {
        const element = document.getElementById(id);
        element.checked = true;
        if (element.classList.contains('collapsible__toggle-group')) {
            toggleCollapsible(element, 'collapsible--selected');
        }
    }

    const reapplyFromStorage = () => {
        const pathname = window.sessionStorage.getItem('reapply-page');
        const filters = window.sessionStorage.getItem('reapply-filters');

        if (pathname && pathname === window.location.pathname && filters) {
            const parsedFilters = JSON.parse(filters);
            window.history.replaceState({
                ...window.history.state,
                filters: parsedFilters
            }, '');
            parsedFilters.forEach(id => reapplyFilter(id));
        }

        window.sessionStorage.removeItem('reapply-page');
        window.sessionStorage.removeItem('reapply-filters');
    }

    if (window.history.state !== null && 'filters' in window.history.state) {
        window.history.state.filters.forEach(id => reapplyFilter(id));
    } else {
        reapplyFromStorage();
    }
});
