const modalClose = function(event)
{
    const modal = this.document.getElementById('modal');
    if (modal && (event.target === modal || event === null)) {
        modal.remove();
    }
}

/**
 * Sends a request to the server for a modal to be opened.
 * Shows the result if response is HTTP_OK.
 *
 * @param {string} target address to send request to
 * @param {object} data   data to send to server
 */
const modalOpen = async function(target, data)
{
    try {
        const response = await fetch(target, {
            method: "GET",
            ...(data !== undefined ? { body: JSON.stringify(data) } : {}),
        });
        if (!response.body) {
            throw new Error('No response from server');
        }

        const parser = new DOMParser();
        const result = parser.parseFromString(response.body, 'text/html');
        if (result.id !== 'modal') {
            throw new Error('Invalid result');
        }

        document.body.appendChild(result);
    } catch (err) {
        console.error(`modalSubmit: ${err.message}`);
        alert('Failed to open modal');
        modalClose(null);
    }
}

/**
 * Submits the form from modal.
 *
 * @param {string} id            id of modal
 * @param {function} onSuccess   callback to call on success
 * @param {string} urlParameter  optional suffix to url, adds '/' in between
 * @param {string} method        form submission method (POST, GET, etc.)
 */
const modalSubmit = async function()
{
    try {
        const form = document.querySelector('#modal form');
        if (!form) {
            throw new Error('Form not found');
        }

        const response = await fetch(form.action, {
            method: form.method,
            contentType: 'application/json',
            body: (new FormData(form)).serialize(),
        });
        if (!response.body) {
            throw new Error('No response from server');
        }

        const parser = new DOMParser();
        const result = parser.parseFromString(response.body, 'text/html');
        if (result.id !== 'modal') {
            throw new Error('Invalid result');
        }

        if (result.className === 'modal') {
            const target = document.getElementById('dynamic');
            target.appendChild(result);
        } else {
            document.body.appendChild(result);
        }
    } catch (err) {
        console.error(`modalSubmit: ${err.message}`);
        alert('Failed to submit modal');
        modalClose(null);
    }
}

// When the user clicks anywhere outside of the modal, close it
window.addEventListener("click", modalClose);
