if (typeof TOKEN === 'undefined') {
    console.error('AJAX calls will fail, no TOKEN provided!');
} else {
    console.debug('Loaded fetch.js');
}

/**
 * FetchAPI extension that supports the CSRF protection. To provide
 * better user experience, it also broadcasts the CSRF token across
 * the user's opened documents (otherwise submissions from one page
 * would prevent all submissions from other pages).
 *
 * @param {string} url        Where to send the request to.
 * @param {object} options    What to pass to the fetch request.
 *
 * @return {Response} returns the result of the fetch request.
 */
const secureFetch = (token => {
    const CSRF_HEADER = 'X-CSRF-TOKEN';

    const channel = new BroadcastChannel('csrf-protection');
    channel.addEventListener('message', (e) => {
        token = e.data;
    });

    return async (url, options = {}) => {
        const secureOptions = {
            ...options,
            headers: {
                ...(options.headers ? options.headers : {}),
                [CSRF_HEADER]: token,
                "X-Requested-With": "XMLHttpRequest",
            }
        }
        const response = await fetch(url, secureOptions).then(response => {
            if (response.headers.get(CSRF_HEADER)) {
                token = response.headers.get(CSRF_HEADER);
                channel.postMessage(token);
            } else {
                console.debug('Undefined token! last: ', token);
            }
            return response;
        });
        return response;
    };
})(TOKEN);

/**
 * Shows an error inside of a modal. Uses templating
 * and text replacement to fill the error message.
 *
 * NOTE: 'String.prototype.fill' has to be defined
 *
 * @param {Error} error gets data here
 */
const showError = (error) => {
    if (typeof ERROR_MODAL === 'undefined') {
        console.debug('Error modal is not defined, showError only alerts!');
        return alert(error.message);
    }
    const errorTemplate = ERROR_MODAL.fill({
        title: error.statusCode ?? 'Ooops',
        message: error.message
    });
    document.body.insertAdjacentHTML('beforeend', errorTemplate);
}

/**
 * Wrapper for secureFetch, instead of returning a response object,
 * processes the response and returns nothing.
 *
 * @param {string} url        Where to send the request to.
 * @param {object} options    What to pass to the fetch request.
 * @param {function} callback The callback to process the response with.
 *
 * @return {void} nothing, uses callback instead
 */
const processedFetch = (url, options, callback) => {
    secureFetch(url, options)
        .then(response => {
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            return response.json();
        })
        .then(response => callback(response))
        .catch(error => showError(error));
}

/**
 * FetchAPI extension to simplify call to upload a file.
 *
 * @param {string} url        Where to send the request to.
 * @param {object} props      What to create formData from, should include selector.
 * @param {function} callback What to do with the result.
 *
 * @return {void} Does not return anything, use callback instead.
 */
const upload = (url, props, callback) => {
    if (url === undefined || callback === undefined || props === undefined) {
        return console.debug('Upload: missing parameters');
    }
    if (props.selector.files[0] === undefined) {
        return console.debug(`${props.fileType} undefined`)
    }

    const formData = new FormData();
    formData.append(props.fileKey ?? "file", props.selector.files[0]);
    formData.append(props.fileTypeKey ?? 'fileType', props.fileType);

    props.selector.value = '';

    processedFetch(url, { method: props.method ?? 'POST', body: formData }, callback);
}
