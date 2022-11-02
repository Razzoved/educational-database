<li class="mb-1">

    <!-- toggle -->
    <button class="btn btn-toggle align-items-center rounded collapsed"
            style="justify-content: center; display: flex"
            data-bs-toggle="collapse"
            data-bs-target="#<?= str_replace(' ', '__', $tag) ?>-collapse"
            aria-expanded="true">
        <!-- triangle right -->
        <svg class="bi bi-caret-right-fill collapse-show"
             xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
        </svg>
        <!-- triangle down -->
        <svg class="bi bi-caret-down-fill collapse-hide"
             xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
        </svg>
        <strong style="font-size: 1.2em;"><?= $tag ?></strong>
    </button>

    <!-- tag list -->
    <div class="collapse show" id='<?= str_replace(' ', '__', $tag) ?>-collapse'>
        <ul class="btn-toggle-nav list-unstyled">
            <?php foreach ($values as $value) : ?>
            <li class="mb-1">
                <form method="post" action='/'>
                    <input type="submit" class="btn btn-fluid btn-outline-secondary w-100"
                           name="<?= $tag ?>[<?= $value ?>]" value="<?= $value ?>"
                           style="white-space:normal; word-wrap: normal;">
                </form>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

</li>
