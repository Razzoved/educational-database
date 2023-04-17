window.addEventListener('DOMContentLoaded', function() {
    var tooltips = document.querySelectorAll('.tooltip__text');

    window.onmousemove = function (e) {
        var x = (e.clientX + 15) + 'px',
            y = (e.clientY + 15) + 'px';
        for (var i = 0; i < tooltips.length; i++) {
            tooltips[i].style.top = y;
            tooltips[i].style.left = x;
        }
    };
});
