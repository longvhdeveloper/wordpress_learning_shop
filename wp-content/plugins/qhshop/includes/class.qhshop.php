<?php
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

class QHShop
{
    protected static $instance;
    protected function __construct()
    {

    }

    protected function __clone()
    {

    }

    public static function get_instance()
    {
        if (self::$instance === null)
        {
            self::$instance = new QHShop();
        }

        return self::$instance;
    }

    public static function run()
    {
        $instance = self::get_instance();

        //start session
        QHShopSession::run();

        //start qhshop proudct
        QHShopProduct::run();

        //start shortcode
        QHShopShortCode::run();

        //start setting
        QHShopSetting::run();

        //run widget cart
        QHShopWidgetCart::run();

        //khoi dong order
        QHShopOrder::run();

        //run widget category
        QHShopWidgetCategory::run();

        return $instance;
    }

    public static function plugin_activation()
    {

    }

    public static function plugin_deactivation()
    {

    }
}
