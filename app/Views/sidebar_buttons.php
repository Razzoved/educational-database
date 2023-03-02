<div class="sidebar">
    <button type="button" class="icon" onclick="sidebar_toggle()">
        <i class="fa fa-bars"></i>
        Toggle tags
    </button>
    <ul>
        <?php foreach ($properties as $tag => $values) : ?>
            <?= view('components/collapsible_list', ['tag' => $tag, 'values' => $values, 'type' => 'button']) ?>
        <?php endforeach; ?>
    </ul>
</div>
<script type="text/javascript">
    function sidebar_toggle() {
        document.querySelector(".sidebar").classList.toggle('responsive');
    }
</script>
