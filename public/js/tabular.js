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
