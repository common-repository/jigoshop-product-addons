<?php

namespace Jigoshop\Extension\ProductAddons;

use Jigoshop\Frontend\Pages;

/**
 * Class Frontend
 * @package Jigoshop\Extension\ProductAddons;
 * @author Krzysztof Kasowski
 */
class Frontend
{
    public function __construct()
    {
        add_action('template_redirect', array($this, 'init'));
    }

    public function init()
    {
        if(Pages::isProduct()) {
            new Frontend\Product();
        }
    }
}
new Frontend();