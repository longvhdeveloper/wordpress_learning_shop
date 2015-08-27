<?php
/*
Plugin Name: QH Shop plugin
Plugin URI:  http://come-stay.vn
Description: Plugin to create shop in wordpress
Version:     1.0
Author:      Jackie
Author URI:  http://come-stay.vn
Text Domain: qhshop
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define(QH_SHOP_VERSION, '1.0');
define(QH_SHOP_MINIMUM_VERSION, '4.1');
define(QH_SHOP_PLUGIN_DIR, plugin_dir_path(__FILE__));
define(QH_SHOP_PLUGIN_URL, plugin_dir_url(__FILE__));
define(QH_SHOP_PLUGIN_LANGUAGES, dirname(plugin_basename(__FILE__)) . 'languages');

require_once(QH_SHOP_PLUGIN_DIR . 'includes/class.qhshop-session.php');
require_once(QH_SHOP_PLUGIN_DIR . 'includes/class.qhshop.php');
require_once(QH_SHOP_PLUGIN_DIR . 'includes/class.qhshop-product.php');
require_once(QH_SHOP_PLUGIN_DIR . 'includes/class.qhshop-order.php');
require_once(QH_SHOP_PLUGIN_DIR . 'includes/class.qhshop-setting.php');
require_once(QH_SHOP_PLUGIN_DIR . 'includes/class.qhshop-shortcode.php');
require_once(QH_SHOP_PLUGIN_DIR . 'includes/class.qhshop-tool.php');
require_once(QH_SHOP_PLUGIN_DIR . 'includes/class.qhshop-widget-cart.php');
require_once(QH_SHOP_PLUGIN_DIR . 'includes/class.qhshop-widget-category.php');

register_activation_hook(__FILE__, array('plugin_activation'));
register_deactivation_hook(__FILE__, array('plugin_dectivation'));

QHShop::run();
