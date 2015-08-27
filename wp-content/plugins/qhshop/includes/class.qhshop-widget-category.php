<?php
//Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

class QHShopWidgetCategory extends WP_Widget
{
    protected static $instance;

    public function __construct()
    {
        parent::__construct(
            'qhshop-category',
            __('QHShop Category', 'qhshop')
        );
    }

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new QHShopWidgetCategory();
        }

        return self::$instance;
    }

    public static function run()
    {
        $instance = self::get_instance();

        add_action('widgets_init', function(){
            register_widget('QHShopWidgetCategory');
        });

        return $instance;
    }

    public function widget($args, $instance)
    {
        $title = ( isset($instance['title']) && !empty($instance['title']) )
            ? apply_filters('widget_title', $instance['title']) : __('Category', 'qhshop');
        $option = get_option('qhshop_setting');
        require(QH_SHOP_PLUGIN_DIR . 'views/frontend/product-widget-category-view.php');
    }

    public function form($instance)
    {
        $title = ( isset($instance['title']) && !empty($instance['title']) )
            ? apply_filters('widget_title', $instance['title']) : __('Cateogry', 'qhshop');
        require(QH_SHOP_PLUGIN_DIR . 'views/backend/product-widget-category-form.php');
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();

        $instance['title'] = ( isset($new_instance['title']) && !empty($new_instance['title']) )
            ? apply_filters('widget_title', $new_instance['title']) : __('Category', 'qhshop');

        return $instance;
    }

}