String.prototype.fill = function(data = undefined) {
    let result = this;
    if (data) {
        console.debug('Data to fill: ', data);
    }
    for (var k in data) {
        result = result.replaceAll(`@${k.toLowerCase()}@`, data[k]);
    }
    return result;
}

String.prototype.html = function(data = undefined) {
    let result = this.fill(data);
    if ((match = result.match(/@[^ @]*@/)) !== null) {
        console.warn('Template not fully filled, please check your arguments');
        console.debug('Found: ', match);
    }
    const parser = new DOMParser();
    return parser.parseFromString(result, 'text/html')?.body.firstElementChild;
};

const modalClose = function(event = null)
{
    const modal = document.getElementById('modal');
    if (modal && (event === null || event.target === modal)) {
        modal.remove();
    }
}

/**
 * Sends a request to the server for a modal to be opened.
 * Shows the result if response is HTTP_OK.
 *
 * @param {string} target   address to send request to
 * @param {string} template html in form of string, contains placeholders
 */
const modalOpen = async (target, template) => {
    try {
        if (target) {
            const response = await fetch(target);
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            template = template.fill({...(await response.json())});
        }
        const element = document.body.appendChild(template.html());
        element.classList.add("modal--visible");
    } catch (err) {
        console.error(`modalSubmit: ${err.message}`);
        alert('Failed to open modal');
        modalClose();
    }
}

const modalSubmit = async () => {
    try {
        const form = document.querySelector('#modal form');
        if (!form) {
            throw new Error('Form not found');
        }

        const response = await fetch(form.action, {
            method: form.method,
            body: new FormData(form),
        });
        if (!response.ok) {
            throw new Error(response.statusText);
        }

        if (!itemTemplate || !items) {
            throw new Error('Internal error, make sure itemTemplate is defined and element with id "items" exists');
        }

        const data = await response.json();
        const template = itemTemplate.html(data);

        const existing = document.getElementById(data.id);
        if (existing) {
            existing.replaceWith(template);
        } else {
            items.insertAdjacentElement('afterbegin', template);
        }
        modalClose();
    } catch (err) {
        modal.querySelector('#error')?.remove();
        modal.querySelector('.modal__body').insertAdjacentElement('afterbegin',
            `<p id="error" style="text-align: center"><strong style="color: red">${err.message}</strong></p>`.html()
        );
    }
}
