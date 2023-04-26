<?php
    /**
     * Partial view that generates property form selections.
     * It requires property_selector javascript file to be loaded.
     *
     * Expects:
     * @param used       already used properties (from database/previous form)
     * @param available  all selectable properties
     */
?>

<div class="property">

    <?php helper('form') ?>

    <!-- selectors and buttons for adding new tags -->
    <div class="property-actions">
        <?= form_dropdown(['title' => 'tagger', 'id' => 'property_tagger'], array_keys($available)) ?>
        <?= form_dropdown(['title' => 'valuator', 'id' => 'property_valuator'], $available[array_key_first($available)]) ?>
        <button id='property_adder' type="button" onclick='addPropertyDivAction()'>Add</button>
    </div>

    <div class="property-groups" id="properties">

        <!-- generate groups for all tags -->
        <?php foreach ($available as $tag => $values) : ?>
            <?= form_label($tag, 'properties-' . $tag, ['class' => 'inactive']) ?>
            <div class="property-group-inactive" id="properties-<?= $tag ?>"></div>
        <?php endforeach;?>

        <!-- load already existing properties -->
        <script type='text/javascript'>
            used = <?= json_encode($used) ?>;
            for (var tag in used)
            {
                for (var i = 0; i < used[tag].length; i++)
                {
                    addPropertyDiv(tag, used[tag][i]);
                }
            }
        </script>

    </div>

    <script>
        <?= include_once(FCPATH . 'js/property-selector') ?>

        let available = <?= json_encode($available) ?>;

        // handle change of tag selector
        document.getElementById('property_tagger').addEventListener('change', () => changeValuator(available));

        // prevent selection of all elements after 3 clicks
        document.getElementById('property_adder').addEventListener('click', (evt) => {
            if (evt.detail === 3) window.getSelection().removeAllRanges();
        });

        // hide fully used tags from selector
        $(document).ready(function() {
            let tagger = document.getElementById('property_tagger');
            for (var option in tagger.options) {
                let tag = tagger.options[option].innerHTML;
                if (tag  !== undefined && getUnused(tag, available[tag]).length === 0) {
                    disableOption(tagger, option);
                }
            }
        });
    </script>

</div>
