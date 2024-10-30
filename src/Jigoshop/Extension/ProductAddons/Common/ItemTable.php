<?php

namespace Jigoshop\Extension\ProductAddons\Common;

use Jigoshop\Frontend\Pages;
use Jigoshop\Helper\Styles;

/**
 * Class Common
 * @package Jigoshop\Extension\ProductAddons\Common;
 * @author Krzysztof Kasowski
 */
class ItemTable
{
    public function __construct()
    {
        add_action('jigoshop\template\shop\cart\after_product_title', array($this, 'afterItemTitle'), 20, 2);
        add_action('jigoshop\template\shop\checkout\after_product_title', array($this, 'afterItemTitle'), 20, 2);
        add_action('admin_enqueue_scripts', function(){
            $screen = get_current_screen();
            if($screen->id == 'shop_order') {
                Styles::add('jigoshop.product_addons.admin.order.item_table',
                    JIGOSHOP_PRODUCT_ADDONS_URL . '/assets/css/admin/order/item_table.css');
            }
        });
        add_action('wp_enqueue_scripts', function(){
            if(Pages::isCart() || Pages::isCheckout() || Pages::isCheckoutPay() || Pages::isCheckoutThankYou()) {
                Styles::add('jigoshop.product_addons.shop.item_table',
                    JIGOSHOP_PRODUCT_ADDONS_URL . '/assets/css/shop/item_table.css');
            }
        });

        add_filter('jigoshop\template\admin\order\item_title', array($this, 'itemTitle'), 20, 3);
    }

    /**
     * @param string $name
     * @param Product $product
     * @param Item $item
     *
     * @return string
     */
    public function itemTitle($name, $product, $item)
    {
        ob_start();
        $this->afterItemTitle($product, $item);
        $name .= ob_get_clean();

        return $name;
    }

    /**
     * @param Product $product
     * @param Item $item
     *
     * @return string
     */
    public function afterItemTitle($product, $item)
    {
        /** @var Item\Meta $meta */
        if($meta = $item->getMeta('addons')) {
            $value = json_decode($meta->getValue(), true);
            $addons = '';
            foreach ($value as $addon) {
                if (!empty($addon['options'])) {
                    $addons .= sprintf('<li>%s: <ul>%s</ul></li>', $addon['name'], join('', array_map(function ($option) {
                        if(filter_var($option['value'], FILTER_VALIDATE_URL)) {
                            return sprintf('<li>%s: <i><a href="%s" target="_blank">%s</a></i></li>',
                                $option['name'],
                                $option['value'],
                                __('Click', 'jigoshop_product_addons'));
                        } else if(empty($option['value'])) {
                            return sprintf('<li>%s</li>', $option['name']);
                        } else {
                            return sprintf('<li>%s: <i>%s</i></li>', $option['name'], $option['value']);
                        }
                    }, $addon['options'])));
                }
            }
            $addons = sprintf('<small><ul class="addons">%s</ul></small>', $addons);

            echo $addons;
        }
    }
}