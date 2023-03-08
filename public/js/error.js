function showError(status) {
    if (status.responseText === undefined) {
        msg = status.statusText === undefined ? status : status.statusText;
        alert('[error]: ' + status);
    } else {
        let modal = document.createElement('div');
        modal.innerHTML = status.responseText;
        modal = modal.firstElementChild;
        if (modal) {
            modal.style.display = "block";
            modal.setAttribute("onclick", 'hideErrorEvent(event, this)')
            document.body.appendChild(modal);
        } else {
            console.error('Could not show error modal', status);
        }
    }
}

function hideErrorEvent(event, modal) {
    if (!modal.querySelector('.modal-content').contains(event.target)) {
        modal.remove();
    }
}