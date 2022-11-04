<li class="mb-1">

    <!-- toggler that shows or hides groups' value list -->
    <a class="btn btn-toggle align-items-center rounded collapsed"
            style="justify-content: center; display: flex"
            data-bs-toggle="collapse"
            data-bs-target="#<?= str_replace(' ', '_', $tag) ?>-collapse"
            aria-expanded="true">

        <!-- triangle right -->
        <i class="bi bi-caret-right-fill collapse-show"></i>

        <!-- triangle down -->
        <i class="bi bi-caret-down-fill collapse-hide"></i>

        <!-- property group tag -->
        <strong style="font-size: 1.2em;"><?= $tag ?></strong>
    </a>

    <!-- collapsible list -->
    <div class="collapse show" id='<?= str_replace(' ', '_', $tag) ?>-collapse'>
        <ul class="btn-toggle-nav list-unstyled">
            <!-- dynamic loading of values -->
             <?php foreach ($values as $value) : ?>
            <li class="mb-1">
                <!-- automatic filter call on all materials when clicked -->
                <form method="post" action='/'>
                    <input type="submit" class="btn btn-fluid btn-outline-secondary w-100"
                           name="<?= $tag ?>[<?= $value ?>]" value="<?= $value ?>"
                           style="white-space:normal; word-wrap: normal">
                </form>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

</li>
