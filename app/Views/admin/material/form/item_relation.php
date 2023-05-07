<?php
    /**
     * Renders one file as a small removable box
     *
     * @param int $id      current index of the file (in given context)
     * @param string $path path to the file's current location
     */
    $id = $id ?? '@id@';
    $url = $url ?? '@url@';
    $path = $path ?? '@path@';
?>
<div id="relation<?= $id ?>">
    <input type="hidden"
        name="relations[<?= $id ?>]"
        value="<?= esc($path) ?>"
        required>
    <a target="_blank" href="<?= $url ?>"><?= $path ?></a>
    <button type="button" onclick="removeById('relation<?= $id ?>')">
        &#10005;
    </button>
</div>
