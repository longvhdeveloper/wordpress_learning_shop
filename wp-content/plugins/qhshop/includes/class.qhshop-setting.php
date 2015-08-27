<?php
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

class QHShopSetting
{
    protected static $instance;
    protected static $options;
    protected static $option_group = 'qhshop_group';
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
            self::$instance = new QHShopSetting();
        }

        return self::$instance;
    }

    public static function run()
    {
        $instance = self::get_instance();

        self::$options = get_option('qhshop_setting');
        //them menu
        add_action('admin_menu', function() use ($instance) {
            add_submenu_page(
                'edit.php?post_type=product',
                __('Product Setting Page', 'qhshop'),
                __('Setting', 'qhshop'),
                'manage_options',
                'qhshop',
                array($instance, 'create_page')
            );
        });

        //dang ky setting
        add_action('admin_init', array($instance, 'register_settings'));

        //
        add_action('admin_init', function(){
            self::page_maker();
        });

        return $instance;
    }

    public static function create_page()
    {
        $option_group = self::$option_group;
        $option = self::$options;
        require(QH_SHOP_PLUGIN_DIR . 'views/backend/product-setting.php');
    }

    public static function register_settings()
    {
        $instance = self::get_instance();
        register_setting(
            'qhshop_group',
            'qhshop_setting',
            array($instance, 'save_settings'));

        return $instance;
    }

    public static function save_settings($input)
    {
        $new_input = array();
        if (isset($input['items_per_page']) && $input['items_per_page'] > 0) {
            $new_input['items_per_page'] = absint($input['items_per_page']);
        } else {
            $new_input['items_per_page'] = 10;
        }

        if (isset($input['currency_symbol']) && $input['currency_symbol'] != '') {
            $new_input['currency_symbol'] = sanitize_text_field($input['currency_symbol']);
        } else {
            $new_input['currency_symbol'] = '$';
        }

        if (isset($input['currency_position']) && $input['currency_position'] != '') {
            $new_input['currency_position'] = sanitize_text_field($input['currency_position']);
        } else {
            $new_input['currency_position'] = 0;
        }

        if (isset($input['thousand_separator']) && $input['thousand_separator'] != '') {
            $new_input['thousand_separator'] = sanitize_text_field($input['thousand_separator']);
        } else {
            $new_input['thousand_separator'] = ',';
        }

        if (isset($input['decimal_separator']) && $input['decimal_separator'] != '') {
            $new_input['decimal_separator'] = sanitize_text_field($input['decimal_separator']);
        } else {
            $new_input['decimal_separator'] = '.';
        }

        if (isset($input['number_of_decimals'])) {
            $new_input['number_of_decimals'] = absint($input['number_of_decimals']);
        } else {
            $new_input['number_of_decimals'] = 2;
        }

        return $new_input;
    }

    public static function page_maker()
    {
        if (isset($_POST['pagemaker'])) {
            global $wpdb;

            if (isset($_POST['pagemaker']['shop'])) {
                $args = array(
                    'post_title' => __('Shop', 'qhshop'),
                    'post_name' => 'shop',
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'comment_status' => 'closed',
                    'post_content' => '[qhshop]',
                    'post_exceprt' => ' ',
                );
                $pages = $wpdb->get_results('select * from '. $wpdb->prefix . 'posts WHERE lower(post_title)=LOWER("shop")');
                if (!$pages) {
                    wp_insert_post($args);
                } else {
                    foreach ($pages as $page) {
                        wp_delete_post($page->ID, true);
                    }

                    wp_insert_post($args);
                }
            }

            if (isset($_POST['pagemaker']['checkout'])) {
                $args = array(
                    'post_title' => __('Checkout', 'checkout'),
                    'post_name' => 'checkout',
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'comment_status' => 'closed',
                    'post_content' => '[qhshop-checkout]',
                    'post_exceprt' => ' ',
                );
                $pages = $wpdb->get_results('select * from '. $wpdb->prefix . 'posts WHERE lower(post_title)=LOWER("checkout")');
                if (!$pages) {
                    wp_insert_post($args);
                } else {
                    foreach ($pages as $page) {
                        wp_delete_post($page->ID, true);
                    }

                    wp_insert_post($args);
                }
            }
        }
    }
}
