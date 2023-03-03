/* Filters from previous search are kept here */
if (typeof lastPost === 'undefined') {
    console.error('Missing last post data, functions from tabular.js will not work properly');
}

function deleteId(id)
{
    let element = document.getElementById(`${id}`);
    if (element) { element.remove(); }
}

function toggleSort(attribute)
{
    let sort = document.createElement('input');
    sort.type = 'hidden';
    sort.name = 'sort';
    sort.value = attribute;

    let sortDir = document.createElement('input');
    sortDir.type = 'hidden';
    sortDir.name = 'sortDir';
    sortDir.value = lastPost['sort'] === attribute && lastPost['sortDir'] === 'ASC' ? 'DESC' : 'ASC';

    let form = document.querySelector('form');
    form.action = "";
    form.appendChild(sort);
    form.appendChild(sortDir);
    form.submit();
}

/**
 * Recursively goes through last post and fills all filters
 * into the elements.
 *
 * @param {string} key full name for the form (appends last key)
 * @param {string} value string OR an array to be searched
 */
function addToFilters(key, value)
{
    if (typeof value == 'object' ) {
        for (var k in value) {
            addToFilters(`${key}[${k}]`, value[k]);
        }
    } else {
        var elems = document.querySelectorAll(`[name='${key}']`) ?? [];
        elems.forEach(e => {
            switch (e.type) {
                case 'checkbox': e.checked = value == 'on'; break;
                case 'text': e.value = value; break;
                default: break;
            }
        });
    }
}

document.addEventListener("DOMContentLoaded", () => {
    for (var p in lastPost) {
        addToFilters(p, lastPost[p]);
    }
});
