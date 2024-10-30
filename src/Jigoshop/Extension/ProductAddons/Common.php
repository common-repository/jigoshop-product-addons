<?php

namespace Jigoshop\Extension\ProductAddons;

use Jigoshop\Entity\Order\Item;
use Jigoshop\Entity\Product;
use Jigoshop\Integration;

/**
 * Class Common
 * @package Jigoshop\Extension\ProductAddons;
 * @author Krzysztof Kasowski
 */
class Common
{
    /**
     * Common constructor.
     */
    public function __construct()
    {
        Integration::addPsr4Autoload(__NAMESPACE__.'\\', __DIR__);
        Integration\Helper\Render::addLocation('product_addons', JIGOSHOP_PRODUCT_ADDONS_DIR);
        add_action('init', array($this, 'init'));
        add_filter('jigoshop\cart\generate_item_key', array($this, 'generateItemKey'), 20, 2);
    }

    /**
     * Init Common components.
     */
    public function init()
    {
        new Common\ItemTable();
    }

    /**
     * @param array $parts
     * @param Item $item
     *
     * @return array
     */
    public function generateItemKey($parts, $item)
    {
        if (!($item->getProduct() instanceof Product\Variable)) {
            if($meta = $item->getMeta('addons')) {
                /** @var $meta Item\Meta */
                $parts[] = $meta->getValue();
            }
        }

        return $parts;
    }
}
new Common();