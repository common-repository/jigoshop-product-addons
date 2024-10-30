<?php
/**
 *
 */
?>
<script type="text/javascript">
    var saved = <?php echo $saved; ?>;
</script>
<script type="text/template" id="tmpl-product-addon">
    <fieldset data-id="{{{ data.id }}}">
        <h4 class="pull-left"><?php _e('Add-on', 'jigoshop_product_addons'); ?></h4>
        <input type="button" class="btn btn-cancel remove-addon pull-right"
               value="<?php _e('Remove Add-on', 'jigoshop_product_addons'); ?>">
        <div class="clear"></div>
        <?php
        \Jigoshop\Admin\Helper\Forms::text(array(
            'id' => '{{{ data.name.id }}}',
            'name' => '{{{ data.name.name }}}',
            'label' => __('Name', 'jigoshop_product_addons'),
            'value' => '{{{ data.name.value }}}',
        ));
        ob_start();
        \Jigoshop\Admin\Helper\Forms::select(array(
            'id' => '{{{ data.type.id }}}',
            'name' => '{{{ data.type.name }}}',
            'label' => __('Type', 'jigoshop_product_addons'),
            'classes' => ['addon-type'],
            'value' => '{{{ data.type.value }}}',
            'options' => array(
                'checkbox' => __('Checkboxes', 'jigoshop_product_addons'),
                'select' => __('Select box', 'jigoshop_product_addons'),
                'text' => __('Short text', 'jigoshop_product_addons'),
                'textarea' => __('Long text', 'jigoshop_product_addons'),
                'datepicker' => __('Datepickers', 'jigoshop_product_addons'),
                'file_upload' => __('File upload', 'jigoshop_product_addons'),
            ),
        ));
        echo preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", ob_get_clean());
        \Jigoshop\Admin\Helper\Forms::textarea(array(
            'id' => '{{{ data.description.id }}}',
            'name' => '{{{ data.description.name }}}',
            'label' => __('Description', 'jigoshop_product_addons'),
            'value' => '{{{ data.description.value }}}',
        ));
        ?>
        <br/>
        <h4 class="pull-left"><?php _e('Options', 'jigoshop_product_addons'); ?></h4>
        <input type="button" class="btn btn-primary add-option pull-right"
               value="<?php _e('Add', 'jigoshop_product_addons'); ?>">
        <div class="clear"></div>
        <div class="options"></div>
    </fieldset>
</script>
<script type="text/template" id="tmpl-product-addon-option">
    <div class="option clearfix" data-id="{{{ data.id }}}">
        <div class="col-sm-4">
            <?php
            \Jigoshop\Admin\Helper\Forms::text(array(
                'id' => '{{{ data.name.id }}}',
                'name' => '{{{ data.name.name }}}',
                'label' => __('Name', 'jigoshop_product_addons'),
                'value' => '{{{ data.name.value }}}',
            ));
            ?>
        </div>
        <div class="col-sm-4">
            <?php
            \Jigoshop\Admin\Helper\Forms::text(array(
                'id' => '{{{ data.price.id }}}',
                'name' => '{{{ data.price.name }}}',
                'label' => __('Price', 'jigoshop_product_addons'),
                'value' => '{{{ data.price.value }}}',
            ));
            ?>
        </div>
        <div class="col-sm-2">
            <?php
            \Jigoshop\Admin\Helper\Forms::checkbox(array(
                'id' => '{{{ data.multiple.id }}}',
                'name' => '{{{ data.multiple.name }}}',
                'classes' => ['multiple'],
                'label' => __('Multiple?', 'jigoshop_product_addons'),
                'checked' => '',
            ));
            ?>
        </div>
        <div class="col-sm-2">
            <input type="button" class="btn btn-default pull-right remove-option"
                   value="<?php _e('X', 'jigoshop_product_addons'); ?>">
        </div>
    </div>
</script>
<div id="product-addons">
    <div class="product-addons"></div>
    <div class="col-xs-12">
        <input type="button" class="btn btn-primary add-addon"
               value="<?php _e('Add New Product Add-on', 'jigoshop_product_addons'); ?>">
    </div>
</div>
<div class="clear"></div>
