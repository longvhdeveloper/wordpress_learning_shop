<?php
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

class QHShopShortCode
{
    protected static $instance;
    protected static $errors;
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
            self::$instance = new QHShopShortCode();
        }

        return self::$instance;
    }

    public static function run()
    {
        $instance = self::get_instance();

        //register style
        add_action('wp_enqueue_scripts', function(){
            if (is_single()) {
                wp_register_style('qhshop_woocommerce_prettyPhoto', QH_SHOP_PLUGIN_URL . 'scripts/css/woocommerce/prettyPhoto.css');
                wp_enqueue_style('qhshop_woocommerce_prettyPhoto');
            }
            wp_register_style('qhshop_woocommerce_layout', QH_SHOP_PLUGIN_URL . 'scripts/css/woocommerce/woocommerce-layout.css');
            wp_register_style('qhshop_woocommerce_smallscreen', QH_SHOP_PLUGIN_URL . 'scripts/css/woocommerce/woocommerce-smallscreen.css');
            wp_register_style('qhshop_woocommerce', QH_SHOP_PLUGIN_URL . 'scripts/css/woocommerce/woocommerce.css', array(
                'qhshop_woocommerce_layout',
                'qhshop_woocommerce_smallscreen'
            ));
            wp_enqueue_style('qhshop_woocommerce');

            wp_register_script('qhshop_js', QH_SHOP_PLUGIN_URL . 'scripts/js/functions.js', array('jquery'));
            wp_localize_script('qhshop_js', 'qhshop', array(
                'url' => admin_url('admin-ajax.php'),
            ));
            wp_enqueue_script('qhshop_js');
        });

        //can thiep body class
        add_filter('body_class', function($classes){
            $classes[] = 'woocommerce';
            $classes[] = 'woocommerce-page';
            return $classes;
        });

        add_filter('query_vars', function($vars){
            $vars[] = 'orderby';
            return $vars;
        });

        //can thiep single template cua WP
        add_filter('single_template', function($single){
            global $post;
            if ($post->post_type == 'product') {
                if (file_exists(QH_SHOP_PLUGIN_DIR . 'views/frontend/product-single.php')) {
                    return QH_SHOP_PLUGIN_DIR . 'views/frontend/product-single.php';
                }
            }
            return $single;
        });
        $option = get_option('qhshop_setting');
        //shortcode api
        add_shortcode('qhshop', array($instance, 'shop'));
        add_shortcode('qhshop-checkout', array($instance, 'checkout'));

        add_action('template_redirect', function(){
            self::$errors = self::is_validate();

            if (empty(self::$errors) || self::$errors == true) {
                self::save_order();
            }
        });

        return $instance;

    }

    public static function shop()
    {
        $option = get_option('qhshop_setting');
        require(QH_SHOP_PLUGIN_DIR . 'views/frontend/product-shop.php');
    }

    public static function checkout()
    {
        $cart = QHShopSession::get('cart');
        $option = get_option('qhshop_setting');
        $errors = self::$errors;
        require(QH_SHOP_PLUGIN_DIR . 'views/frontend/product-checkout.php');
    }

    public static function is_validate()
    {
        $errors = array();
        if (isset($_POST['checkout']) && !wp_verify_nonce($_POST['_wpnonce'], 'qhshop_checkout')) {
            $errors[] = __('Security error', 'qhshop');
            return $errors;
        }

        if (isset($_POST['checkout'])) {
            foreach ($_POST['checkout'] as $input => $value) {
                if (empty($value)) {
                    $errors[] = __("$input field is required", 'qhshop');
                }
            }
            return $errors;
        }

        return true;

    }

    public static function save_order()
    {
        if (isset($_POST['checkout'])) {
            $cart = QHShopSession::get('cart');

            if ($cart) {
                $post_id = wp_insert_post(array(
                    'post_status' => 'private',
                    'post_type' => 'product_order',
                    'post_title' => ' ;',
                    'post_content' => ' ',
                    'post_excerpt' => ' '
                ), true);

                update_post_meta($post_id, 'order', array(
                    'checkout' => $_POST['checkout'],
                    'cart' => $cart,
                    'status' => 0
                ));

                QHShopSession::destroy('cart');
                wp_redirect(get_permalink(get_page_by_path('shop')));
            }
        }
    }
}