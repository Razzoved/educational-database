<div class="sidebar">
    <button type="button" class="icon" onclick="sidebar_toggle()">
        <i class="fa fa-bars"></i>
        Toggle filters
    </button>
    <button type="button" class="sidebar-reset-btn" onclick="{ document.querySelectorAll('.collapsible input[type=checkbox]').forEach(e => e.checked=false); document.querySelectorAll('input[name=search]').forEach(e => e.value=''); }">
        Reset filters
    </button>
    <ul>
        <?php foreach ($properties as $tag => $values) : ?>
            <?= view('components/collapsible_list', ['tag' => $tag, 'values' => $values, 'type' => 'checkbox']) ?>
        <?php endforeach; ?>
    </ul>
</div>
<script type="text/javascript">
    function sidebar_toggle() {
        document.querySelector(".sidebar").classList.toggle('responsive');
    }
</script>
