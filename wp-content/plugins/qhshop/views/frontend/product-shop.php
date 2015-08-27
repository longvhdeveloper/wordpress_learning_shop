<?php
$paged = get_query_var('paged') ? get_query_var('paged') : 0;
$category = get_query_var('product_category') ? get_query_var('product_category') : 0;
$args = array(
    'post_type' => 'product',
    'posts_per_page' => (int)$option['items_per_page'],
    'paged' => $paged
);

if ($category) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'product_category',
            'field' => 'slug',
            'terms' => $category,
        ),
    );
}
if (get_query_var('orderby') && get_query_var('orderby') != 'date') {
    $args['meta_key'] = 'sale_price';
    $args['orderby'] = 'meta_value_num';

    switch (get_query_var('orderby')) {
        case 'price-asc':
            $args['order'] = 'ASC';
            break;
        case 'price-desc':
            $args['order'] = 'DESC';
            break;
    }
}
$products = new WP_Query($args);
?>
<div id="container">
    <div id="content" role="main">

        <p class="woocommerce-result-count">
            <?php printf(_n('Showing all 1 result', 'Showing all %s results', $products->found_posts, 'qhshop'), $products->found_posts); ?>        </p>

        <form class="woocommerce-ordering" method="get">
            <select name="orderby" class="orderby">
                <?php
                $select_arr = array(
                    'date' => __('Sort by newness', 'qhshop'),
                    'price-asc' => __('Sort by price: low to high', 'qhshop'),
                    'price-desc' => __('Sort by price: high to low', 'qhshop')
                );
                foreach ($select_arr as $key => $value) {
                    if (get_query_var('orderby') == $key) {
                        printf('<option selected value="%s">%s</option>', add_query_arg('orderby', $key, get_permalink()), $value);
                    } else {
                        printf('<option value="%s">%s</option>', add_query_arg('orderby', $key, get_permalink()), $value);
                    }
                }
                ?>
            </select>
        </form>

        <ul class="products">
            <?php
            if ($products->have_posts()) {
                while ($products->have_posts()) {
                    $products->the_post();
                    $data = get_post_meta(get_the_ID(), 'product')[0];
                    ?>
                    <li class="<?php echo implode(' ', get_post_class()) ?>">
                        <a href="<?php echo get_permalink(); ?>">
                            <?php
                            if (has_post_thumbnail()) {
                                echo get_the_post_thumbnail(get_the_ID(), 'thumbnail');
                            } else {
                                printf('<img src="%s" alt="%s" />', QH_SHOP_PLUGIN_URL . 'images/placeholder.png', __('No photo', 'qhshop'));
                            }
                            ?> </a>

                        <h3><?php echo get_the_title(); ?></h3>
                <span class="price"><del><span class="amount"><?php echo QHShopTool::get_currency($data['regular_price'], $option); ?></span></del> <ins><span
                            class="amount"><?php echo QHShopTool::get_currency($data['sale_price'], $option); ?></span></ins></span>
                        <a href="<?php echo add_query_arg('add-to-cart', get_the_ID()); ?>" class="button add_to_cart_button product_type_simple" data-item-id="<?php the_ID(); ?>"><?php  _e('Add to cart', 'qhshop') ?></a>
                    </li>
                    <?php
                }
                wp_reset_postdata();
            }
            ?>
        </ul>


    </div>
</div>
<?php
$big = 999999999; // need an unlikely integer

$pagination =  paginate_links( array(
    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
    'format' => '?paged=%#%',
    'current' => max( 1, get_query_var('paged') ),
    'total' => $products->max_num_pages
) );

$pagination = str_replace('#038;', '&', $pagination);

if (!empty($pagination)) {
?>
    <nav class="navigation pagination" role="navigation">
<!--        <h2 class="screen-header-text">--><?php //echo __('Products navigation', 'qhshop'); ?><!--</h2>-->
        <div class="nav-links">
            <?php
            echo $pagination;
            ?>
        </div>
    </nav>
<?php
}
?>