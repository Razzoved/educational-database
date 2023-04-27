
window.addEventListener('DOMContentLoaded', function() {
    // PREPARE TOOLTIP RENDERING TARGET
    const tooltip = document.querySelector('.tooltip');
    if (!tooltip) {
        console.debug('Tooltip target is not present in document');
        return;
    }
    window.onmousemove = function (e) {
        tooltip.style.left = (e.clientX + 15) + 'px';
        tooltip.style.top = (e.clientY + 15) + 'px';
        // console.log(tooltip.style.top, tooltip.style.left);
    };

    // PREPARE ALL POSSIBLE TOOLTIPS
    const tooltips = document.querySelectorAll('*[data-tooltip]');
    if (!tooltips) {
        console.debug('No tooltips present in document');
        return;
    }

    tooltips.forEach((t) => {
        t.addEventListener('mouseenter', (event) => {
            console.debug('show', t.getAttribute('data-tooltip'));
            tooltip.querySelector('.tooltip__text').innerHTML = t.getAttribute('data-tooltip');
            tooltip.classList.replace('tooltip--inactive', 'tooltip--active');
        });
        t.addEventListener('mouseleave', (event) => {
            console.debug('hide', t.getAttribute('data-tooltip'));
            tooltip.classList.replace('tooltip--active', 'tooltip--inactive');
        });
    });
});
