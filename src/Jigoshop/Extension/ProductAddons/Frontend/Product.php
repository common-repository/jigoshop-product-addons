<?php

namespace Jigoshop\Extension\ProductAddons\Frontend;

use Jigoshop\Entity\Order\Item;
use Jigoshop\Helper\Scripts;
use Jigoshop\Helper\Styles;
use Jigoshop\Integration;
use Jigoshop\Integration\Helper\Render;

/**
 * Class Product
 * @package Jigoshop\Extension\ProductAddons\Frontend;
 * @author Krzysztof Kasowski
 */
class Product
{
    public function __construct()
    {
        add_filter('jigoshop\cart\add', array($this, 'addToCart'), 20, 2);

        add_action('jigoshop\template\product\before_cart', array($this, 'render'));
        add_action('wp_enqueue_scripts', function () {
            Styles::add('jigoshop.addons.shop.product',
                JIGOSHOP_PRODUCT_ADDONS_URL . '/assets/css/shop/product.css'
            );
            Styles::add('jigoshop.addons.vendor.checkbox',
                JIGOSHOP_PRODUCT_ADDONS_URL . '/assets/css/vendor/magic-check.min.css'
            );
            Styles::add('jigoshop.vendors.datepicker',
                JIGOSHOP_URL . '/assets/css/vendors/datepicker.css',
                array('jigoshop.shop.product')
            );
            Scripts::add('jigoshop.vendors.datepicker',
                JIGOSHOP_URL . '/assets/js/vendors/datepicker.js',
                array('jquery', 'jigoshop.shop.product')
            );
        });
    }

    /**
     * @param Item $item
     * @param \Jigoshop\Entity\Product\* $product
     *
     * @return Item
     */
    public function addToCart($item, $product)
    {
        if (isset($_POST['jigoshop'])) {
            $this->prepareMetaFromPost($product, $item);
        }
        if(isset($_FILES['jigoshop'])) {
            $this->prepareMetaFromFiles($product, $item);
        }
        $item->setKey(Integration::getProductService()->generateItemKey($item));

        return $item;
    }
    
    /**
     * @param \Jigoshop\Entity\Product\* $product
     */
    public function render($product)
    {
        $addons = get_post_meta($product->getId(), 'addons', true);
        if ($addons) {
            Render::output('product_addons', 'shop/product/addons', array(
                'addons' => $addons,
            ));
        }
    }

    private function prepareMetaFromPost($product, $item)
    {
        $meta = array();
        $addons = get_post_meta($product->getId(), 'addons', true);
        $price = $item->getPrice();
        foreach ($_POST['jigoshop']['addon'] as $addonKey => $options) {
            $meta[$addonKey] = array(
                'name' => $addons[$addonKey]['name'],
                'options' => array(),
            );
            if(is_array($options)) {
                foreach ($options as $optionKey => $value) {
                    if ($addons[$addonKey]['type'] != 'checkbox' || $value == 'on') {
                        if($addons[$addonKey]['type'] == 'checkbox') {
                            $value = '';
                        }
                        if(isset($_POST['jigoshop']['addon_times'],
                            $_POST['jigoshop']['addon_times'][$addonKey],
                            $_POST['jigoshop']['addon_times'][$addonKey][$optionKey])) {
                            $times = (int)$_POST['jigoshop']['addon_times'][$addonKey][$optionKey];
                            $addons[$addonKey]['options'][$optionKey]['name'] .= ' x'.$times;
                            $addons[$addonKey]['options'][$optionKey]['price'] *= $times;
                        }
                        $meta[$addonKey]['options'][] = array(
                            'name' => $addons[$addonKey]['options'][$optionKey]['name'],
                            'price' => $addons[$addonKey]['options'][$optionKey]['price'],
                            'value' => $value
                        );
                        $price += $addons[$addonKey]['options'][$optionKey]['price'];
                    }
                }
            } else {
                $meta[$addonKey]['options'][] = array(
                    'name' => $addons[$addonKey]['options'][$options]['name'],
                    'price' => $addons[$addonKey]['options'][$options]['price'],
                    'value' => '',
                );
                $price += $addons[$addonKey]['options'][$options]['price'];
            }
        }
        $item->setPrice($price);
        if($itemMeta = $item->getMeta('addons')) {
            $meta = array_merge(json_decode($itemMeta->getValue(), true), $meta);
            $itemMeta->setValue(json_encode($meta));
        } else {
            $item->addMeta(new Item\Meta('addons', json_encode($meta)));
        }
    }

    private function prepareMetaFromFiles($product, $item) {
        require_once(ABSPATH.'wp-admin/includes/file.php' );
        $meta = array();
        $addons = get_post_meta($product->getId(), 'addons', true);
        $price = $item->getPrice();
        foreach($_FILES['jigoshop']['name']['addon'] as $addonKey => $options) {
            $meta[$addonKey] = array(
                'name' => $addons[$addonKey]['name'],
                'options' => array(),
            );
            foreach ($options as $optionKey => $value) {
                if ($value) {
                    $file = array(
                        'name'     => $_FILES['jigoshop']['name']['addon'][$addonKey][$optionKey],
                        'type'     => $_FILES['jigoshop']['type']['addon'][$addonKey][$optionKey],
                        'tmp_name' => $_FILES['jigoshop']['tmp_name']['addon'][$addonKey][$optionKey],
                        'error'    => $_FILES['jigoshop']['error']['addon'][$addonKey][$optionKey],
                        'size'     => $_FILES['jigoshop']['size']['addon'][$addonKey][$optionKey],
                    );
                    $upload = wp_handle_upload($file, array('test_form' => false));
                    $meta[$addonKey]['options'][] = array(
                        'name' => $addons[$addonKey]['options'][$optionKey]['name'],
                        'price' => $addons[$addonKey]['options'][$optionKey]['price'],
                        'value' => $upload['url'],
                    );
                    $price += $addons[$addonKey]['options'][$optionKey]['price'];
                }
            }
        }
        $item->setPrice($price);
        if($itemMeta = $item->getMeta('addons')) {
            $meta = array_merge(json_decode($itemMeta->getValue(), true), $meta);
            $itemMeta->setValue(json_encode($meta));
        } else {
            $item->addMeta(new Item\Meta('addons', json_encode($meta)));
        }
    }
}