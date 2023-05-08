const upload = (callback, props) => {
    if (callback === undefined || props === undefined) {
        return console.debug('Upload: missing parameters');
    }
    if (props.selector.files[0] === undefined) {
        return console.debug(`${props.fileType} undefined`)
    }

    const formData = new FormData();

    formData.append("file", props.selector.files[0]);
    formData.append('fileType', props.fileType);

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
