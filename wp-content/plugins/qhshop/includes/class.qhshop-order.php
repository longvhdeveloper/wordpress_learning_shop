<?php
// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

class QHShopOrder
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
        if (self::$instance === null) {
            self::$instance = new QHShopOrder();
        }

        return self::$instance;
    }

    public static function run()
    {
        $instance = self::get_instance();

        //dang ky post type
        add_action('init', function () {
            self::register_post_type();
        });

        //dang ky meta box
        add_action('admin_init', function(){
            self::register_metabox();
            //delete_post_meta(4, 'product');
        });

        // luu metabox
        add_action('save_post', function($post_id){
            self::save_meta_box_data($post_id);
        });

        //dang ky column
        add_filter('manage_product_order_posts_columns', array($instance, 'register_columns'));

        //hien thi danh sach product
        add_action('manage_product_order_posts_custom_column', array($instance, 'list_orders'), 10, 2);


        return $instance;
    }

    public static function register_post_type()
    {
        $instance = self::get_instance();

        $labels = array(
            'name' => _x('Orders', 'Post Type General Name', 'qhshop'),
            'singular_name' => _x('Order', 'Post Type Singular Name', 'qhshop'),
            'menu_name' => __('Order', 'qhshop'),
            'name_admin_bar' => __('Post Type', 'qhshop'),
            'parent_item_colon' => __('Parent Item:', 'qhshop'),
            'all_items' => __('Orders', 'qhshop'),
            'add_new_item' => __('Add New Item', 'qhshop'),
            'add_new' => __('Add New', 'qhshop'),
            'new_item' => __('New Item', 'qhshop'),
            'edit_item' => __('Edit Order', 'qhshop'),
            'update_item' => __('Update Order', 'qhshop'),
            'view_item' => __('View Item', 'qhshop'),
            'search_items' => __('Search Order', 'qhshop'),
            'not_found' => __('Not found', 'qhshop'),
            'not_found_in_trash' => __('Not found in Trash', 'qhshop'),
        );
        $args = array(
            'label' => __('Order', 'qhshop'),
            'description' => __('Post Type Description', 'qhshop'),
            'labels' => $labels,
            'supports' => false,
            //'taxonomies'          => array( 'category', 'post_tag' ),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=product',
            'menu_position' => 5,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'capability_type' => 'page',
            'capabilities' => array(
                'create_posts' => false,
            ),
            'map_meta_cap' => true
        );
        register_post_type('product_order', $args);

        return $instance;
    }


    public static function register_metabox()
    {
        $instance = self::get_instance();
        add_meta_box('order detail', __('Order Detail', 'qhshop'), function ($post) {
            $data = get_post_meta($post->ID, 'order')[0];
            $option = get_option('qhshop_setting');
            require(QH_SHOP_PLUGIN_DIR . 'views/backend/product-order-metabox.php');
        }, 'product_order', 'normal', 'core');

        return $instance;
    }

    public static function register_columns($columns)
    {
        $columns = array(
            'cb' => $columns['cb'],
            'order' => '#Order',
            'purchased' => __('Purchased', 'qhshop'),
            'status' => __('Status', 'qhshop'),
            'total_price' => __('Total', 'qhshop'),
            'date' => $columns['date']
        );
        return $columns;
    }

    public static function save_meta_box_data($post_id)
    {
        /*
         * We need to verify this came from our screen and with proper authorization,
         * because the save_post action can be triggered at other times.
         */

        // Check if our nonce is set.
        if (!isset($_POST['qh_order_security'])) {
            return;
        }

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($_POST['qh_order_security'], $post_id)) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions.
        if (isset($_POST['post_type']) && 'product_order' == $_POST['post_type']) {

            if (!current_user_can('edit_page', $post_id)) {
                return;
            }

        } else {

            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        /* OK, it's safe for us to save the data now. */

        // Make sure that it is set.
        if (!isset($_POST['order'])) {
            return;
        }

        //xu ly du lieu
        if (isset($_POST['order']) && !empty($_POST['order'])) {
            $data = get_post_meta($post_id, 'order')[0];
            if ($data) {
                $status = is_numeric($_POST['order']['status']) ? absint($_POST['order']['status']) : '0';
                update_post_meta($post_id, 'order', array(
                    'checkout' => $data['checkout'],
                    'cart' => $data['cart'],
                    'status' => $status,
                ));
            }
        } else {
            return;
        }
    }

    public static function list_orders($columns, $post_id)
    {
        $data = get_post_meta($post_id, 'order')[0];

        $option = get_option('qhshop_setting');
        switch ($columns) {
            case 'order':
                $link = get_edit_post_link($post_id);
                echo '<a href="'.$link.'">#'.$post_id.'</a>';
                break;
            case 'purchased':
                echo count($data['cart']);
                break;
            case 'status':
                $select_arr = array(
                    '0' => __('Pending', 'qhshop'),
                    '1' => __('Processing', 'qhshop'),
                    '2' => __('Complete', 'qhshop'),
                );
                foreach ($select_arr as $key => $value) {
                    if (isset($data['status']) && $data['status'] == $key) {
                        echo $value;
                    }
                }
                break;
            case 'total_price' :
                $total = 0;
                foreach ($data['cart'] as $item) {
                    $total += $item['item_quantity'] * $item['item_price'];
                }
                QHShopTool::get_currency($total, $option);
                break;
        }
    }

}
