const modalClose = function (id)
{
    let modal = document.getElementById(id);
    if (modal) {
        modal.style.display = "none";
        modalReset(id);
    } else {
        console.error(`modalClose: id[${id}] not found`);
    }
}

/**
 * Prepares a given modal's values, and then displays it.
 *
 * @param {*} id
 * @param {*} data
 */
function modalOpen(id, data)
{
    if (!Array.isArray(data)) {
        console.debug(`modalOpen: Invalid parameter, plese provide an array`, data);
        return;
    }

    let modal = document.getElementById(id);
    if (!modal) {
        console.error(`modalOpen: modal of id[${id}] not found`);
        return;
    }

    data.forEach(function (item, index) {
        let target = modal.querySelector(`#${index}`);
        if (!target) {
            console.debug(`modalOpen: target of id[${index}] not found`);
            return;
        }
        target.value = item;
    });

    resourceModal.style.display = "block";
}

function modalReset(id)
{
    let modal = document.getElementById(id);
    if (!modal) {
        console.error(`modalReset: modal of id[${id}] not found`);
        return;
    }
    modal.querySelectorAll("input")?.forEach(function (input) {
        input.value = "";
    });
}

/**
 * Tries to open the error modal if it exists, if not
 * then tries to create a simple error modal. If it
 * fails then sends out an alert.
 *
 * @param {*} status   message to show - string|response
 * @param {string} id  id of error modal
 */
function modalError(status, id='error-modal') {
    let msg = "";
    if (typeof status === 'string') {
        msg = `<div class="modal"><div class="modal-content">${status}</div></div>`;
    } else if (status.responseText === undefined) {
        msg = status.statusText;
    } else {
        msg = status.responseText ?? 'UNKNOWN ERROR';
    }

    let target = document.getElementById(id)
    if (!target) {
        let modal = document.createElement('div');
        modal.innerHTML = msg;
        modal = modal.firstElementChild;
        if (modal) {
            modal.style.display = "block";
            modal.setAttribute("onclick", 'modalErrorHide(event, this)')
            document.body.appendChild(modal);
        } else {
            console.error('Could not show error modal');
            alert(`ERROR: ${msg}`);
        }
        return;
    }

    let msgTarget = target.querySelector(".error-message");
    if (msgTarget) {
        msgTarget.innerText = msg;
    }
}

function modalErrorHide(event, modal) {
    if (!modal.querySelector('.modal-content').contains(event.target)) {
        modal.remove();
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
function modalSubmit(id, onSuccess, urlParameter = '', method = 'POST')
{
    let form = $(`#${id} form`);
    $.ajax({
        type: method,
        url: form.attr('action') + (urlParameter == '' ? '' : `/${urlParameter}`),
        dataType: 'json',
        data: form.serialize(),
        success: function(result) {
            onSuccess(result);
            modalClose(id);
        },
        error: (jqXHR) => {
            modalError(jqXHR);
            modalClose(id);
        }
    })
}
