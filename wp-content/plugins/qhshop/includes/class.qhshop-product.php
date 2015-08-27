<?php
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

class QHShopProduct
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
            self::$instance = new QHShopProduct();
        }

        return self::$instance;
    }

    public static function run()
    {
        $instance = self::get_instance();

        //dang ky post type
        add_action('init', function(){
            self::register_post_type();
            self::register_taxonomies();
        });

        //dang ky meta box
        add_action('admin_init', function(){
            self::register_metabox();
            //delete_post_meta(4, 'product');
        });

        //dang ky script
        add_action('admin_enqueue_scripts', function(){
            self::register_script();
        });

        // luu metabox
        add_action('save_post', function($post_id){
            self::save_meta_box_data($post_id);
        });

        //dang ky column
        add_filter('manage_product_posts_columns', array($instance, 'register_columns'));

        //hien thi danh sach product
        add_action('manage_product_posts_custom_column', array($instance, 'list_products'), 10, 2);

        //can thiep chuc nang mo thumbnail
        add_action('after_setup_theme', function(){
            add_theme_support('post_thumbnail');
        });

        return $instance;
    }

    public static function register_post_type()
    {
        $instance = self::get_instance();

        $labels = array(
            'name'                => _x( 'Products', 'Post Type General Name', 'qhshop' ),
            'singular_name'       => _x( 'Product', 'Post Type Singular Name', 'qhshop' ),
            'menu_name'           => __( 'Product', 'qhshop' ),
            'name_admin_bar'      => __( 'Post Type', 'qhshop' ),
            'parent_item_colon'   => __( 'Parent Item:', 'qhshop' ),
            'all_items'           => __( 'All Items', 'qhshop' ),
            'add_new_item'        => __( 'Add New Item', 'qhshop' ),
            'add_new'             => __( 'Add New', 'qhshop' ),
            'new_item'            => __( 'New Item', 'qhshop' ),
            'edit_item'           => __( 'Edit Item', 'qhshop' ),
            'update_item'         => __( 'Update Item', 'qhshop' ),
            'view_item'           => __( 'View Item', 'qhshop' ),
            'search_items'        => __( 'Search Item', 'qhshop' ),
            'not_found'           => __( 'Not found', 'qhshop' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'qhshop' ),
        );
        $args = array(
            'label'               => __( 'Product', 'qhshop' ),
            'description'         => __( 'Post Type Description', 'qhshop' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', ),
            //'taxonomies'          => array( 'category', 'post_tag' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
        );
        register_post_type( 'product', $args );

        return $instance;
    }

    public static function register_taxonomies()
    {
        $instance = self::get_instance();

        $labels = array(
            'name'                       => _x( 'Categories', 'Taxonomy General Name', 'qhshop' ),
            'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'qhshop' ),
            'menu_name'                  => __( 'Categories', 'qhshop' ),
            'all_items'                  => __( 'All Items', 'qhshop' ),
            'parent_item'                => __( 'Parent Item', 'qhshop' ),
            'parent_item_colon'          => __( 'Parent Item:', 'qhshop' ),
            'new_item_name'              => __( 'New Item Name', 'qhshop' ),
            'add_new_item'               => __( 'Add New Item', 'qhshop' ),
            'edit_item'                  => __( 'Edit Item', 'qhshop' ),
            'update_item'                => __( 'Update Item', 'qhshop' ),
            'view_item'                  => __( 'View Item', 'qhshop' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'qhshop' ),
            'add_or_remove_items'        => __( 'Add or remove items', 'qhshop' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'qhshop' ),
            'popular_items'              => __( 'Popular Items', 'qhshop' ),
            'search_items'               => __( 'Search Items', 'qhshop' ),
            'not_found'                  => __( 'Not Found', 'qhshop' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => array(
                'slug' => 'product-categories'
            ),
        );
        register_taxonomy( 'product_category', array( 'product' ), $args );


        //register tag for product
        $labels = array(
            'name'                       => _x( 'Tags', 'Taxonomy General Name', 'qhshop' ),
            'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'qhshop' ),
            'menu_name'                  => __( 'Tags', 'qhshop' ),
            'all_items'                  => __( 'All Items', 'qhshop' ),
            'parent_item'                => __( 'Parent Item', 'qhshop' ),
            'parent_item_colon'          => __( 'Parent Item:', 'qhshop' ),
            'new_item_name'              => __( 'New Item Name', 'qhshop' ),
            'add_new_item'               => __( 'Add New Item', 'qhshop' ),
            'edit_item'                  => __( 'Edit Item', 'qhshop' ),
            'update_item'                => __( 'Update Item', 'qhshop' ),
            'view_item'                  => __( 'View Item', 'qhshop' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'qhshop' ),
            'add_or_remove_items'        => __( 'Add or remove items', 'qhshop' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'qhshop' ),
            'popular_items'              => __( 'Popular Items', 'qhshop' ),
            'search_items'               => __( 'Search Items', 'qhshop' ),
            'not_found'                  => __( 'Not Found', 'qhshop' ),
        );
        $rewrite = array(
            'slug'                       => 'product-tags',
            'with_front'                 => true,
            'hierarchical'               => false,
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => $rewrite,
        );
        register_taxonomy( 'product_tags', array( 'product' ), $args );

        return $instance;
    }

    public static function register_metabox()
    {
        $instance = self::get_instance();
        add_meta_box('product detail', __('Product Detail', 'qhshop'), function($post){
            $data = get_post_meta($post->ID, 'product')[0];
            $option = get_option('qhshop_setting');
            require(QH_SHOP_PLUGIN_DIR . 'views/backend/product-metabox.php');
        }, 'product', 'normal', 'core');

        return $instance;
    }

    public static function register_script()
    {
        //admin css
        wp_register_style('qhshop_admin_css', QH_SHOP_PLUGIN_URL . '/scripts/css/admin-style.css');
        wp_enqueue_style('qhshop_admin_css');
        //admin js
        wp_register_script('qhshop_admin_js', QH_SHOP_PLUGIN_URL . 'scripts/js/admin-js.js', array('jquery'));
        wp_enqueue_script('qhshop_admin_js');
    }

    public static function register_columns($columns)
    {
        $columns = array(
            'cb' => $columns['cb'],
            'title' => $columns['title'],
            'post_thumb' => __('Image', 'qhshop'),
            'sale_price' => __('Sale Price', 'qhshop'),
            'taxonomy-product_category' => $columns['taxonomy-product_category'],
            'date' => $columns['date']
        );
        return $columns;
    }

    public static function save_meta_box_data( $post_id )
    {
        /*
         * We need to verify this came from our screen and with proper authorization,
         * because the save_post action can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( ! isset( $_POST['qh_product_security'] ) ) {
            return;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['qh_product_security'], $post_id ) ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions.
        if ( isset( $_POST['post_type'] ) && 'product' == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }

        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }

        /* OK, it's safe for us to save the data now. */

        // Make sure that it is set.
        if ( ! isset( $_POST['product'] ) ) {
            return;
        }

        //xu ly du lieu
        $data = $_POST['product'];
        $data['regular_price'] = is_numeric($data['regular_price']) ? abs($data['regular_price']) : 0;
        $data['sale_price'] = is_numeric($data['sale_price']) ? abs($data['sale_price']) : 0;
        $data['product_quantity'] = is_numeric($data['product_quantity']) ? absint($data['product_quantity']) : 0;
        $data['attributes'] = isset($data['attributes']) ? self::make_attributes($data['attributes']) : array();
        //luu vao csdl
        update_post_meta($post_id, 'product', $data);
        //
        update_post_meta($post_id, 'sale_price', $data['sale_price']);
    }

    public static function make_attributes($attributes = array())
    {
        foreach ($attributes as $key => $attribute) {
            if (empty($attribute['name']) || empty($attribute['value'])) {
                unset($attributes[$key]);
            } else {
                $attributes[$key]['name'] = sanitize_text_field($attributes[$key]['name']);
                $attributes[$key]['value'] = sanitize_text_field($attributes[$key]['value']);
            }
        }
        $attributes = array_values($attributes);
        return $attributes;
    }

    public static function list_products($columns, $post_id)
    {
        $data = get_post_meta($post_id, 'product')[0];
        $option = get_option('qhshop_setting');
        switch ($columns) {
            case 'sale_price':
                echo QHShopTool::get_currency($data['sale_price'], $option);
                break;
            case 'post_thumb':
                if (has_post_thumbnail($post_id)) {
                    echo get_the_post_thumbnail($post_id, 'thumbnal');
                }
            break;
        }
    }

}
