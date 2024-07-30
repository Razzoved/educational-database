<form class="search" id="search" autocomplete="off" method="get" action="<?= $action ?? '' ?>" data-url="<?= $url ?>">
    <input class="search__bar" name="search" value="" placeholder="Enter search value" />
    <button class="search__submit fas fa-search" type="button"></button>
    <ul class="search__suggestions"></ul>
</form>

<script src="<?= base_url("js/search.js") ?>" type="module"></script>