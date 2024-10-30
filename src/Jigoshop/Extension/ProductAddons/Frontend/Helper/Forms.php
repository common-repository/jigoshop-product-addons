<?php

namespace Jigoshop\Extension\ProductAddons\Frontend\Helper;

use Jigoshop\Helper\Product;
use Jigoshop\Helper\Forms as FormsHelper;
use Jigoshop\Integration\Render;

/**
 * Class Forms
 * @package Jigoshop\Extension\ProductAddons\Frontend\Helper;
 * @author Krzysztof Kasowski
 */
class Forms
{
    private static $renderScript = true;

    /**
     * @param int $id
     * @param string $type
     * @param array $options
     */
    public static function field($id, $type, $options)
    {
        switch($type) {
            case 'checkbox':
                self::checkbox($id, $options);
                break;
            case 'text':
                self::text($id, $options);
                break;
            case 'textarea':
                self::textarea($id, $options);
                break;
            case 'datepicker':
                self::datepicker($id, $options);
                break;
            case 'file_upload':
                self::file($id, $options);
                break;
            default:
                self::select($id, $options);
            break;
        }
    }

    /**
     * @param int $id
     * @param array $options
     */
    private static function select($id, $options)
    {
        FormsHelper::select(array(
            'id' => 'jigoshop_addon_'.$id,
            'name' => 'jigoshop[addon]['.$id.']',
            'label' => '',
            'placeholder' => __('Choose an option ...', 'jigoshop_product_addon'),
            'options' => array_map(function($option){
                return sprintf('%s (%s)', $option['name'], Product::formatPrice((float)$option['price']));
            }, $options),
            'value' => '-1',
        ));
    }

    private static function text($id, $options)
    {
        for($i = 0; $i < sizeof($options); $i++) {
            FormsHelper::text(array(
                'id' => 'jigoshop_addon_'.$id.'_'.$i,
                'name' => 'jigoshop[addon]['.$id.']['.$i.']',
                'label' => sprintf(__('%s (%s)', 'jigoshop_product_addon'), $options[$i]['name'],
                    Product::formatPrice((float)$options[$i]['price'])),
            ));
        }
    }

    /**
     * @param $id
     * @param $options
     */
    private static function checkbox($id, $options)
    {
        for($i = 0; $i < sizeof($options); $i++) {
            Render::output('product_addons', 'shop/product/addons/single/checkbox', array(
                'id' => 'jigoshop_addon_'.$id.'_'.$i,
                'name' => 'jigoshop[addon]['.$id.']['.$i.']',
                'nameTimes' => 'jigoshop[addon_times]['.$id.']['.$i.']',
                'title' => sprintf(__('%s (%s)', 'jigoshop_product_addon'),
                    $options[$i]['name'],
                   Product::formatPrice((float)$options[$i]['price'])),
                'multiple' => isset($options[$i]['multiple']) && $options[$i]['multiple'] == 'on',
            ));
        }
    }

    /**
     * @param $id
     * @param $options
     */
    private static function textarea($id, $options)
    {
        for($i = 0; $i < sizeof($options); $i++) {
            FormsHelper::textarea(array(
                'id' => 'jigoshop_addon_'.$id.'_'.$i,
                'name' => 'jigoshop[addon]['.$id.']['.$i.']',
                'label' => sprintf(__('%s (%s)', 'jigoshop_product_addon'),
                    $options[$i]['name'],
                    Product::formatPrice((float)$options[$i]['price'])),
                'rows' => 2,
            ));
        }
    }

    /**
     * @param $id
     * @param $options
     */
    private static function datepicker($id, $options)
    {
        for($i = 0; $i < sizeof($options); $i++) {
            FormsHelper::text(array(
                'id' => 'jigoshop_addon_'.$id.'_'.$i,
                'name' => 'jigoshop[addon]['.$id.']['.$i.']',
                'label' => sprintf(__('%s (%s)', 'jigoshop_product_addon'),
                    $options[$i]['name'],
                    Product::formatPrice((float)$options[$i]['price'])),
                'rows' => 2,
                'classes' => array('aaaa'),
            ));
            echo '<script>
                jQuery("#jigoshop_addon_'.$id.'_'.$i.'").datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    container: ".jigoshop_addon_'.$id.'_'.$i.'_field",
                    orientation: "top left",
                    todayBtn: "linked",
                });
            </script>';
        }
    }

    /**
     * @param $id
     * @param $options
     */
    public static function file($id, $options)
    {
        if(self::$renderScript) {
            echo '<script>jQuery(\'.jigoshop form\').attr(\'enctype\', \'multipart/form-data\');</script>';
            self::$renderScript = false;
        }

        for($i = 0; $i < sizeof($options); $i++) {
            FormsHelper::text(array(
                'id' => 'jigoshop_addon_'.$id.'_'.$i,
                'name' => 'jigoshop[addon]['.$id.']['.$i.']',
                'label' => sprintf(__('%s (%s)', 'jigoshop_product_addon'),
                    $options[$i]['name'],
                    Product::formatPrice((float)$options[$i]['price'])),
                'rows' => 2,
                'type' => 'file',
                'classes' => array('aaaa'),
            ));
        }
    }
}