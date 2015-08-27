<?php
//Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

class QHShopWidgetCart extends WP_Widget
{
    protected static $instance;

    public function __construct()
    {
        parent::__construct(
            'qhshop-cart',
            __('QHShop Cart', 'qhshop')
        );
    }

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new QHShopWidgetCart();
        }

        return self::$instance;
    }

    public static function run()
    {
        $instance = self::get_instance();

        add_action('widgets_init', function(){
            register_widget('QHShopWidgetCart');
        });

        add_filter('query_vars', function($vars){
            $vars[] = 'add-to-cart';
            $vars[] = 'delete-cart';
            return $vars;
        });

        //them gio hang
        add_action('wp', function(){
            if (get_query_var('add-to-cart')) {
                self::add_to_cart(get_query_var('add-to-cart'));
            }

            if (get_query_var('delete-cart')) {
                self::delete_cart(get_query_var('delete-cart'));
                if (isset($_SERVER['HTTP_REFERER'])) {
                    $old_url = explode('?', $_SERVER['HTTP_REFERER']);
                    wp_redirect($old_url[0]);
                } else {
                    wp_redirect(home_url('/'));
                }
            }
        });

        //dang ky ajax
        add_action('wp_ajax_add-to-cart', array($instance, 'add_to_cart_ajax'));
        add_action('wp_ajax_nopriv_add-to-cart', array($instance, 'add_to_cart_ajax'));

        add_action('wp_ajax_delete-cart', array($instance, 'delete_cart_ajax'));
        add_action('wp_ajax_nopriv_delete-cart', array($instance, 'delete_cart_ajax'));

        return $instance;
    }

    public function widget($args, $instance)
    {
        $cart = QHShopSession::get('cart');
        $title = ( isset($instance['title']) && !empty($instance['title']) )
            ? apply_filters('widget_title') : __('Cart', 'qhshop');
        $option = get_option('qhshop_setting');
        require(QH_SHOP_PLUGIN_DIR . 'views/frontend/product-widget-cart-view.php');
    }

    public function form($instance)
    {
        $title = ( isset($instance['title']) && !empty($instance['title']) )
            ? apply_filters('widget_title', $instance['title']) : __('Cart', 'qhshop');
        require(QH_SHOP_PLUGIN_DIR . 'views/backend/product-widget-cart-form.php');
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();

        $instance['title'] = ( isset($new_instance['title']) && !empty($new_instance['title']) )
            ? apply_filters('widget_title', $new_instance['title']) : __('Cart', 'qhshop');

        return $instance;
    }

    public static function add_to_cart($id)
    {
        $product = get_post_meta($id, 'product')[0];
        $session = QHShopSession::get('cart');

        //kiem tra so luong
        if (isset($_POST['quantity']) && absint($_POST['quantity']) < $product['product_quantity']) {
            $quantity = $_POST['quantity'];
        } else {
            $quantity = 1;
        }

        if (!isset($session[$id])) {
            $session[$id] = array(
                'item_name' => get_the_title($id),
                'item_quantity' => $quantity,
                'item_price' => $product['sale_price'],
            );

            QHShopSession::update('cart', $session);
        } else {
            $session[$id]['item_quantity'] += $quantity;
            QHShopSession::update('cart', $session);
        }

        return self::$instance;
    }

    public static function delete_cart($id)
    {
        $session = QHShopSession::get('cart');
        if (isset($session[$id]) && is_numeric($id)) {
            unset($session[$id]);
            QHShopSession::update('cart', $session);
        } elseif (!empty($session) && $id == 'all') {
            QHShopSession::destroy('cart');
        }

        return self::$instance;
    }

    public static function add_to_cart_ajax()
    {
        $json = array(
            'status' => 0
        );
        if (isset($_POST['item_id']) && !empty($_POST['item_id'])) {
            self::add_to_cart($_POST['item_id']);
            $cart = QHShopSession::get('cart');
            $option = get_option('qhshop_setting');
            ob_start();
            require(QH_SHOP_PLUGIN_DIR . 'views/frontend/ajax/qhshop-cart-list.php');
            $result = ob_get_clean();
            $json = array(
                'cart' => $result,
                'status' => 1
            );
        }

        wp_send_json_success($json);
    }

    public static function delete_cart_ajax()
    {
        $json['status'] = 0;
        if (isset($_POST['item_id']) && !empty($_POST['item_id'])) {
            $cart = QHShopSession::get('cart');
            $option = get_option('qhshop_setting');
            if (!empty($cart)) {
                QHShopSession::destroy($_POST['item_id']);
                $total = 0;
                foreach ($cart as $item) {
                    $total += $item['item_quantity'] * $item['item_price'];
                }
                $json['subtotal'] = QHShopTool::get_currency($total, $option);
                $json['status'] = 1;
            }
        }
        wp_send_json_success($json);
    }
}