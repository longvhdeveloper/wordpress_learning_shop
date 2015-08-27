<?php
$product_categories = get_terms('product_category', array(
    'orderby' => 'name',
    'hide_empty' => 0
));
?>
<aside id="<?php echo $args['widget_id']; ?>" class="widget woocommerce widget_shopping_cart" style="opacity: 1;"><h2
        class="widget-title"><?php echo $title; ?></h2>

    <div class="widget_shopping_cart_content">
        <?php
        if (!empty($product_categories)) {
        ?>

        <ul class="cart_list product_list_widget ">
            <?php
            foreach ($product_categories as $product_category) {
            ?>
                <li><a href="<?php echo add_query_arg('product_category', $product_category->slug, get_permalink(get_page_by_path('shop')))?>"><?php echo $product_category->name; ?></a></li>
            <?php
            }
            ?>

        </ul>
            <?php
        }
        ?>
        <!-- end product list -->


    </div>
</aside>