let side = document.querySelector('.side');
let sideExpand = document.querySelector('.side_expand');
let sideListItem = document.querySelectorAll('.side_listitem');

// Add EventListener to expand icon
sideExpand.addEventListener('click', () => {
    side.classList.toggle('side-closed');
});

// Loop through LIs
sideListItem.forEach(link => link.addEventListener('click', listActive));

// Adding and remove the -active class
function listActive() {
    sideListItem.forEach(link => link.classList.remove('side_listitem-active'));
    this.classList.add('side_listitem-active');
}
