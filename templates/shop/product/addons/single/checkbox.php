<?php
/**
 *
 */
?>
<div class="form-group checkbox <?= $id; ?>">
    <?php if($multiple) : ?>
    <input class="pull-right form-control" type="number" step="1" min="1" name="<?= $nameTimes; ?>" value="1">
    <span class="pull-right times">&times;</span>
    <?php endif; ?>
    <input type="hidden" name="<?= $name; ?>" value="off">
    <input class="magic-checkbox" type="checkbox" name="<?= $name; ?>" id="<?= $id; ?>" value="on">
    <label class="pull-right" for="<?= $id; ?>"></label>
    <label class="pull-right text" for="<?= $id; ?>"><?= $title; ?></label>
</div>
