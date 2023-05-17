<div id="none" hidden>
    <h2 style="text-align:center">None were found.</h2>
    <hr style="margin-top: 1rem; margin-bottom: 1rem">

    <script type="text/javascript">
        const none = document.getElementById('none');

        const showNoneIfEmpty = () => {
            const items = document.getElementById('items');
            if (items.childElementCount === 1) {
                none.removeAttribute('hidden', '');
            }
        }

        document.addEventListener('delete-item', showNoneIfEmpty);
        document.addEventListener('append-item', () => none.setAttribute('hidden', ''));
        document.addEventListener('DOMContentLoaded', showNoneIfEmpty);
    </script>
</div>
