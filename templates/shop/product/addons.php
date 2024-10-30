<?php
use Jigoshop\Integration\Helper\Render;

/**
 * @var $addons
 */
?>
<div class="addons">
    <?php for($i = 0; $i < sizeof($addons); $i++) : ?>
        <?php Render::output('product_addons', 'shop/product/addons/single',
            array_merge($addons[$i], array('id' => $i))); ?>
    <?php endfor; ?>
</div>
