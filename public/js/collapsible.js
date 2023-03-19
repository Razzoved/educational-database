async function toggleCollapsible(element)
{
    let parent = element.closest('.collapsible');
    if (!parent) {
        console.error('Group is invalid, no collapsible parent found.', element);
        return;
    }
    parent.classList.toggle('collapsible--collapsed');
}

async function toggleOverflow(element)
{
    element.innerHTML = element.innerHTML.indexOf("more") !== -1
        ? element.innerHTML.replace('more', 'less')
        : element.innerHTML.replace('less', 'more');
    let parent = element.closest('.collapsible');
    if (!parent) {
        console.error('Group is invalid, no collapsible parent found.', element);
        return;
    }
    parent.classList.toggle('collapsible--no-overflow');
}

async function toggleGroup(element)
{
    let parent = element.closest('.page__group');
    if (!parent) {
        console.error('Group is invalid, no group parent found.', element);
        return;
    }
    parent.classList.toggle('page__group--show');
}

async function resetFilters()
{
    document.querySelectorAll('.collapsible input[type=checkbox]').forEach(e => e.checked=false);
    document.querySelectorAll('input[name=search]').forEach(e => e.value='');

    if (typeof sendSearch !== undefined) {
        sendSearch();
    }
}
