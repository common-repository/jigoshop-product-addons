<?php

namespace Jigoshop\Extension\ProductAddons\Admin;

use Jigoshop\Helper\Scripts;
use Jigoshop\Integration\Helper\Render;

/**
 * Class Product
 * @package Jigoshop\Extension\ProductAddons\Admin;
 * @author Krzysztof Kasowski
 */
class Product
{
    /**
     * Product constructor.
     */
    public function __construct()
    {
        add_filter('jigoshop\admin\product\menu', array($this, 'addMenu'));
        add_filter('jigoshop\admin\product\tabs', array($this, 'addTab'), 10, 2);
        add_action('jigoshop\service\product\save', array($this, 'save'), 20);
        add_action('admin_enqueue_scripts', function(){
            $screen = get_current_screen();
            if($screen->base == 'post' && $screen->id == 'product') {
                Scripts::add('jigoshop.addons.admin.product',
                    JIGOSHOP_PRODUCT_ADDONS_URL.'/assets/js/admin/product.js',
                    array('jquery', 'wp-util')
                );
            }
        });
    }

    /**
     * @param array $menu
     *
     * @return array
     */
    public function addMenu($menu)
    {
        $menu['product_addons'] = array(
            'label' => __('Product Add-ons', 'jigoshop_product_addons'),
            'visible' => true,
        );

        return $menu;
    }

    /**
     * @param $tabs
     * @param $product
     * @return mixed
     */
    public function addTab($tabs, $product)
    {
        $saved = json_encode(get_post_meta($product->getId(), 'addons', true));
        $tabs['product_addons'] = Render::get('product_addons', 'admin/product/tab', array(
            'saved' => $saved
        ));

        return $tabs;
    }

    /**
     * @param \Jigoshop\Entity\Product $product
     */
    public function save($product)
    {
        $addons = array();
        if(isset($_POST['product']['addons'])) {
            $addons = array_values($_POST['product']['addons']);
            for($i = 0; $i < sizeof($addons); $i++) {
                if(isset($addons[$i]['options'])) {
                    //Remove options with no price.
                    $addons[$i]['options'] = array_values(
                        array_filter(
                            $addons[$i]['options'],
                            function($option){
                                return $option['price'] !== '';
                            }
                        )
                    );
                }
            }
        }
        update_post_meta($product->getId(), 'addons', $addons);
    }
}