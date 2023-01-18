function removeById(elementId)
{
    console.log('removing element with id:', elementId);
    document.getElementById(elementId).remove();
}

function createRemoveButton(removalTarget)
{
    let button = document.createElement("button");

    button.type = "button";
    button.innerHTML = '&#10005;';
    button.classList = "btn col-auto";
    button.style.fontWeight = "bold";

    button.onclick = function() {
        let container = removalTarget.parentElement;
        container.removeChild(removalTarget);
    }

    return button;
}
