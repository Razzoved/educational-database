<li class="mb-1">

    <!-- toggle -->
    <button class="btn btn-toggle align-items-center rounded collapsed"
            data-bs-toggle="collapse"
            data-bs-target="#<?= $tag ?>-collapse"
            aria-expanded="true">
        <span class="navbar-toggler-icon"></span>
        <strong style="font-size: 1.2em;"><?= $tag ?></strong>
    </button>

    <!-- tag list -->
    <div class="collapse show" id="<?= $tag ?>-collapse">
        <ul class="btn-toggle-nav list-unstyled">
            <?php foreach ($values as $value) : ?>
            <form method="post" action='/'>
                <li class="mb-1">
                <input type="submit" class="btn btn-fluid btn-outline-secondary w-100"
                       name="<?= $tag ?>[<?= $value ?>]" value="<?= $value ?>"
                       style="white-space:normal; word-wrap: normal;">
                </li>
            </form>
            <?php endforeach; ?>
        </ul>
    </div>

</li>