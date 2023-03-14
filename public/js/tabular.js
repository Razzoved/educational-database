/* Filters from previous search are kept here */
if (typeof lastSearch === 'undefined') {
    console.error('Missing lastSearch data, functions from tabular.js will not work properly');
}

function deleteId(id)
{
    console.log(id);
    let element = document.getElementById(`${id}`);
    if (element) { element.remove(); }
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
        var elems = document.querySelectorAll(`[name='${key.replace('_', ' ')}']`) ?? [];
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
    for (var p in lastSearch) {
        addToFilters(p, lastSearch[p]);
    }
});
