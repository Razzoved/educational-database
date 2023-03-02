<div class="flex-shrink-0">
    <ul class="list-unstyled ps-0" style="height: fit-content">
        <?php foreach ($properties as $tag => $values) : ?>
            <?= view('components/collapsible_list', ['tag' => $tag, 'values' => $values, 'type' => 'button']) ?>
        <?php endforeach; ?>
    </ul>
</div>
<script type="text/javascript">
    function sidebar_toggle() {
        var x = document.querySelector(".sidebar");
        if (x.className === "sidebar") {
            x.className += " responsive";
        } else {
            x.className = "sidebar";
        }
    }
</script>
