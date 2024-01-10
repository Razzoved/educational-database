<form class="search" id="search" autocomplete="off" method="get" action="<?= $action ?? '' ?>" data-url="<?= $url ?>">
    <input class="search__bar" name="search" value="" placeholder="Enter search value" />
    <button class="search__submit fas fa-search" type="button" />
    <ul class="search__suggestions"></ul>
</form>

<script type="text/javascript">
    <?= include_once(PUBLICPATH . '/js/search.js') ?>
</script>