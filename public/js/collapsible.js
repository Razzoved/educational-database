async function toggleGroup(element)
{
    let parent = element.closest('.collapsible');
    if (!parent) console.debug('invalid group', element);
    parent.classList.toggle('closed');
}

async function toggleOverflow(element)
{
    element.innerHTML = element.innerHTML.indexOf("more") !== -1
        ? element.innerHTML.replace('more', 'less')
        : element.innerHTML.replace('less', 'more');
    let parent = element.closest('.collapsible');
    if (!parent) console.debug('invalid group', element);
    parent.classList.toggle('overflow-closed');
}

async function toggleSidebar()
{
    document.querySelector(".sidebar").classList.toggle('responsive');
}

async function resetFilters()
{
    document.querySelectorAll('.collapsible input[type=checkbox]').forEach(e => e.checked=false);
    document.querySelectorAll('input[name=search]').forEach(e => e.value='');
}
