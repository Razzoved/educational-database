const showError = (error) => {
    const errorTemplate = `<?= view('errors/modal') ?>`
        .replace('@title@', error.statusCode ?? 'Ooops')
        .replace('@message@', error.message);
    document.body.insertAdjacentHTML('beforeend', errorTemplate);
}

const upload = (callback, props) => {
    if (callback === undefined || props === undefined) {
        return console.debug('Upload: missing parameters');
    }
    if (props.selector.files[0] === undefined) {
        return console.debug(`${props.fileType} undefined`)
    }

    const formData = new FormData();

    formData.append(props.fileKey ?? "file", props.selector.files[0]);
    formData.append(props.fileTypeKey ?? 'fileType', props.fileType);

    props.selector.value = '';

    fetch(props.url, { method: props.method ?? 'POST', body: formData })
        .then(response => {
            if (!response.ok) {
                throw Error(response.statusText);
            }
            return response.json();
        })
        .then(response => callback(response))
        .catch(error => showError(error));
}

const uploadURL = (callback, element, whereTo) => {
    if (callback === undefined || element === undefined || whereTo === undefined) {
        return console.debug('UploadURL: missing parameters');
    }
    if (element.value === "" || !element.value.match('^' + element.pattern)) {
        return console.debug('Invalid url');
    }

    body = new FormData();
    body.append('type', element.name);
    body.append('value', element.value);

    fetch(whereTo, { method: 'POST', body })
        .then(response => {
            if (!response.ok) {
                throw Error(response.statusText)
            }
            return response.json();
        })
        .then(response => {
            element.value = response.value;
            callback(response);
        })
        .catch(error => showError(error));
}
