<?php
/**
 * Plugin Name: Jigoshop Product Add-ons
 * Plugin URI: https://www.jigoshop.com/product/jigoshop-product-add-ons/
 * Description: Jigoshop Product Add-ons lets you add extra options to products which the user can select. Add-ons can be checkboxes, a select box, or custom input. Each option can optionally be given a price which is added to the cost of the product.
 * Version: 2.1
 * Requires at least: 4.0
 * Tested up to: 5.0.3
 * Author: Jigo Ltd
 * Author URI: https://www.jigoshop.com
 */
// Define plugin name
define('JIGOSHOP_PRODUCT_ADDONS_NAME', 'Jigoshop Product Add-ons');
add_action('plugins_loaded', function () {
    load_plugin_textdomain('jigoshop_product_addons', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    if (class_exists('\Jigoshop\Core')) {
        //Check version.
        if (\Jigoshop\addRequiredVersionNotice(JIGOSHOP_PRODUCT_ADDONS_NAME, '2.0')) {
            return;
        }
        // Define plugin directory for inclusions
        define('JIGOSHOP_PRODUCT_ADDONS_DIR', dirname(__FILE__));
        // Define plugin URL for assets
        define('JIGOSHOP_PRODUCT_ADDONS_URL', plugins_url('', __FILE__));
        //Init components.
        require_once(JIGOSHOP_PRODUCT_ADDONS_DIR . '/src/Jigoshop/Extension/ProductAddons/Common.php');
        if (is_admin()) {
            require_once(JIGOSHOP_PRODUCT_ADDONS_DIR . '/src/Jigoshop/Extension/ProductAddons/Admin.php');
        } //else {
        require_once(JIGOSHOP_PRODUCT_ADDONS_DIR . '/src/Jigoshop/Extension/ProductAddons/Frontend.php');
        //}
    } elseif (class_exists('jigoshop')) {
        //Check version.
        if (jigoshop_add_required_version_notice(JIGOSHOP_PRODUCT_ADDONS_NAME, '1.17')) {
            return;
        }
        // Define plugin directory for inclusions
        define('JIGOSHOP_PRODUCT_ADDONS_DIR', dirname(__FILE__) . '/Jigoshop1x');
        // Define plugin URL for assets
        define('JIGOSHOP_PRODUCT_ADDONS_URL', plugins_url('', __FILE__) . '/Jigoshop1x');
        //Init components.
        require_once(JIGOSHOP_PRODUCT_ADDONS_DIR . '/product-addons.php');
    } else {
        add_action('admin_notices', function () {
            echo '<div class="error"><p>';
            printf(__('%s requires Jigoshop plugin to be active. Code for plugin %s was not loaded.',
                'jigoshop_product_addons'), JIGOSHOP_PRODUCT_ADDONS_NAME, JIGOSHOP_PRODUCT_ADDONS_NAME);
            echo '</p></div>';
        });
    }
});
