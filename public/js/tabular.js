/* Filters from previous search are kept here */
if (typeof lastSearch === 'undefined') {
    console.error('Missing lastSearch data, functions from tabular.js will not work properly');
}

/**
 * For each entry in data, replaces its index inside the
 * template with the value of the entry.
 *
 * @param {string} template template with placeholders
 * @param {array}  data     placeholder indexed data
 * @return {boolean|string} filled out template, or false.
 */
const fillTemplate = (template, data) => {
    return data.length > 0 && data.reduce(
        (prev, cur, curIdx) => prev.replace(curIdx, cur),
        template
    );
}

/**
 * Tries to fill the template with the data and appends
 * it to the 'items' element.
 *
 * @param {string} template template with placeholders
 * @param {array}  data     placeholder indexed data
 */
const appendData = (template, data) => {
    const items = document.getElementById('items');
    if (!items) {
        console.error('appendData: items element not found');
        return;
    }
    template = fillTemplate(template, data);
    if (!template) {
        console.error('appendData: template filling failed');
        return;
    }
    items.insertAdjacentElement('afterbegin', template);
}

const deleteId = (id) => {
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
const addToFilters = (key, value) => {
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
