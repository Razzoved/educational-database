/* REQUIRES dynamics.js to be loaded */

/**
 * Returns all values that were not added to the page under the
 * id of 'properties-TAG'.
 *
 * @param {string} tag - tag of the properties we want to get
 * @param {array} properties - all available properties that will be filtered
 */
function getUnused(tag, properties)
{
    groupElement = document.getElementById(`properties-${tag}`);

    if (groupElement === undefined || !groupElement.hasChildNodes()) {
        return properties;
    }

    let used = [];
    groupElement.childNodes.forEach(node => {
        let option = node.querySelector('input[type=hidden]');
        if (option && option.value) used.push(option.value);
    });

    return properties.filter(value => !used.includes(value));
}

/**
 * Returns the next property that has not been added yet.
 * If no such property exists returns '' that by default
 * acts as a deselector for dropdown menu.
 *
 * @param {array} options values to search in
 */
function getNextNonDisabled(options)
{
    for (var option in options) {
        if (!options[option].disabled) {
            return option;
        }
    }
    return '';
}

/**
 * Intented for use as a listener.
 *
 * Function that loads values corresponding to selected tag to value selector.
 *
 * @param {array} properties Available properties (ie. array of all tags
 *                           and their values)
 */
function changeValuator(properties)
{
    let tagger = document.getElementById('property_tagger');
    let valuator = document.getElementById('property_valuator');

    if (tagger.options[tagger.value] === undefined) {
        console.debug('no options available for this tag');
        return;
    }

    let tag = tagger.options[tagger.value].text;

    var child = valuator.firstChild;
    while (child) {
        valuator.removeChild(child);
        child = valuator.firstChild;
    }

    let index = 0;
    getUnused(tag, properties[tag]).forEach(value => {
        let option = document.createElement('option');
        option.text = value;
        option.value = index++;
        valuator.appendChild(option);
    });
}

/**
 * Disables value for a given index from selection in target selector (needs to be present).
 *
 * @param {HTMLSelectElement} target selector to disable selection in
 * @param {string} index index to disable
 * @param {boolean} isIndex if false then the value will first be searched for
 */
function disableOption(target, value, isIndex = true)
{
    let index = isIndex ? value : findIndexInNodes(target.options, value);

    console.debug('disabling:', value);

    target.options[index].disabled = true;
    target.value = getNextNonDisabled(target.options);
}

/**
 * Enables given value for selection in the target selector (if it is present).
 *
 * @param {HTMLSelectElement} target selector to enable selection in
 * @param {string} value value to enable
 * @param {boolean} isIndex if false then the value will first be searched for
 */
function enableOption(target, value, isIndex = true)
{
    let index = isIndex ? value : findIndexInNodes(target.options, value);
    if (index < 0 || index > target.options.length) return;

    console.debug('enabling:', value);

    target.options[index].disabled = false;
    if (target.value == '') {
        target.value = value;
    }
}

/**
 * Returns the index of the value if it exists in the nodes.
 *
 * @param {array|Iterator} nodes nodes to search through
 * @param {string} text value to search for
 */
function findIndexInNodes(nodes, text)
{
    for (var i = 0; i < nodes.length; i++) {
        if (nodes[i].text === text) {
            return i;
        }
    }
    return -1;
}

/**
 * Checks whether the given group is empty or not, and sets the
 * visibility accordingly (including label).
 *
 * @param {HTMLElement} group
 */
function handlePropertyGroupDisplay(group)
{
    let label = group.parentElement.querySelector('label[for="' + group.id + '"]');

    if (group.childNodes.length > 0) {
        if (label) label.classList.remove('inactive');
        group.classList.remove('property-group-inactive');
        group.classList.add('property-group');
    } else {
        if (label) label.classList.add('inactive');
        group.classList.remove('property-group');
        group.classList.add('property-group-inactive');
    }
}

/**
 * This function is targeted for dynamically adding properties into the page
 * by the user (button).
 *
 * Adds a single div element that acts as a property. This includes
 * the form data, displayed text and removal button. Removes it from
 * the value selector.
 *
 * @param {string} tag   tag of property for grouping
 * @param {string} value value of property that will be added
 */
function addPropertyDivAction()
{
    let valuator = document.getElementById('property_valuator');

    if (valuator.value === '') {
        console.debug('no options left');
        return;
    };

    let value = valuator.options[valuator.value].text;

    let tagger = document.getElementById('property_tagger');
    let tag = tagger.options[tagger.value].text;

    addPropertyDiv(tag, value);
    disableOption(valuator, valuator.value);

    if (valuator.value === '') {
        disableOption(tagger, tagger.selectedIndex);
        tagger.dispatchEvent(new Event('change'));
    }
}

/**
 * Adds a single div element that acts as a property. This includes the
 * form data, displayed text and removal button.
 *
 * @param {string} tag   tag of property for grouping
 * @param {string} value value of property that will be added
 */
function addPropertyDiv(tag, value)
{
    console.debug('adding property div:', tag, '<--', value);

    let container = document.getElementById('properties-' + tag);

    let span = document.createElement('span');
    span.classList = 'property-group-item';

    let wrapper = document.createElement('div');
    wrapper.classList = 'property-group-wrapper';

    let removeButton = createRemoveButton(wrapper) // from dynamics;

    span.appendChild(createHiddenProperty(tag, value));
    span.appendChild(createProperty(value));
    span.appendChild(removeButton);

    removeButton.addEventListener('click', () => {
        let tagger = document.getElementById('property_tagger');
        let valuator = document.getElementById('property_valuator');

        enableOption(valuator, value, false);
        enableOption(tagger, tag, false);

        if (tagger.value === '') {
            tagger.value = getNextNonDisabled(tagger.options);
            tagger.dispatchEvent(new Event('change'));
        }

        handlePropertyGroupDisplay(container);
    });

    wrapper.appendChild(span);
    container.appendChild(wrapper);
    handlePropertyGroupDisplay(container);
}

/**
 * Generates a new form element of property with the given value.
 *
 * @param {string} tag   type of property (ie. grouping)
 * @param {string} value set value of the property
 */
function createHiddenProperty(tag, value)
{
    let hidden = document.createElement('input');

    hidden.type = 'hidden';
    hidden.name = `properties[${tag}][]`;
    hidden.value = value;

    return hidden;
}

/**
 * Generates a new property element with the given value.
 *
 * @param {string} value text to display
 */
function createProperty(value)
{
    let property = document.createElement('p');
    property.innerHTML = value;
    return property;
}
