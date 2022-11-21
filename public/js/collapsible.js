{
    let clpsExpand = document.querySelectorAll('.clps_expand');
    if (clpsExpand.length > 0) {
        clpsExpand.item(clpsExpand.length - 1).addEventListener('click', toggleGroup);
    }

    function toggleGroup(ev) {
        if (!(ev.target instanceof Element)) return;
        let parent = ev.target.parentElement;

        if (parent && !(parent.classList.contains('clps'))) parent = parent.parentElement;
        if (!parent || !(parent.classList.contains('clps'))) return;

        parent.classList.toggle('clps-closed');
    }
}
