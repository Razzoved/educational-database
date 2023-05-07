<?php
    /**
     * Renders one file as a small removable box
     *
     * @param int $id      current index of the file (in given context)
     * @param string $path path to the file's current location
     */
    $id = $id ?? '@id@';
    $path = $path ?? '@path@';
?>

<div class="form__group form__group--horizontal" id="link<?= $id ?>">
    <input name="links[]"
        type="url"
        class="form__input"
        pattern="https://.*"
        placeholder="https://example.com"
        value="<?= $path ?>"
        readonly
        required>
    <button class="form__input" type="button" onclick="removeLink('link<?= $id ?>')" ?>>
        &#10005;
    </button>
</div>
