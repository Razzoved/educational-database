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
