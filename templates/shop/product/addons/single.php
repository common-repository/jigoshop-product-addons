<?php
use Jigoshop\Helper\Product as ProductHelper;
use Jigoshop\Extension\ProductAddons\Frontend\Helper\Forms as FormsHelper;
/**
 * @var int $id unique number of current addon.
 * @var array $addon current addon.
 */
?>
<div class="single" data-id="<?php echo $id; ?>">
    <h3><?php echo $name; ?></h3>
    <div class="description"><?php echo $description; ?></div>
    <fieldset>
        <?php FormsHelper::field($id, $type, $options); ?>
    </fieldset>
</div>
