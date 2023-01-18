async function toggleGroup(element)
{
    let parent = element.parentElement;
    if (!parent || !(parent.classList.contains('clps'))) console.debug('invalid group', element);
    parent.classList.toggle('clps-closed');
}

async function toggleOverflow(element)
{
    element.innerHTML = element.innerHTML.indexOf("more") !== -1
        ? element.innerHTML.replace('more', 'less')
        : element.innerHTML.replace('less', 'more');
    let parent = element.parentElement.parentElement;

    if (!parent || !(parent.classList.contains('clps'))) console.debug('invalid group', element);

    parent.classList.toggle('clps-closed');
}
