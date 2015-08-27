<aside id="<?php echo $args['widget_id'] ?>" class="widget woocommerce widget_shopping_cart qh_shopping_cart" style="opacity: 1;<?php echo (!empty($cart)) ? 'display:block' : 'display:none'; ?>;">
    <h2
        class="widget-title"><?php echo $title; ?></h2>

    <div class="widget_shopping_cart_content">

        <ul class="cart_list product_list_widget" id="qhshop-cart-list">
            <?php
            $total = 0;
            if (!empty($cart)) {
                foreach ($cart as $id => $item) {
                    $total += $item['item_price'] * $item['item_quantity'];
            ?>
            <li class="qh_shopping_cart">
                <a href="<?php echo get_permalink($id); ?>">
                <?php
                if (has_post_thumbnail($id)) {
                    echo get_the_post_thumbnail($id, 'thumbnail', array(
                        'class' => 'attachment-shop_thumbnail wp-post-image'
                    ));
                }
                echo $item['item_name'];
                ?>

                </a>
                <span class="quantity"><?php echo $item['item_quantity'] ?> X <span class="amount"><?php echo QHShopTool::get_currency($item['item_price'], $option); ?></span>
                <a href="<?php add_query_arg('delete-cart', $id); ?>" style="display:inline;color:red;" class="delete_button" data-item-id="<?php echo $id; ?>">[X]</a>
            </li>
            <?php
                }
            } else {

            }
            ?>

        </ul>
        <!-- end product list -->


        <p class="total"><strong><?php _e('Subtotal:', 'qhshop') ?></strong> <span class="amount subtotal"><?php echo QHShopTool::get_currency($total, $option); ?></span></p>


        <p class="buttons">
        </p>

        <form method="post" action="<?php echo get_permalink(); ?>" style="display:inline-block">
            <input type="hidden" name="delete-cart" value="all">
            <button class="button wc-forward" type="submit"><?php _e('Delete', 'qhshop') ?></button>
        </form>
        <a href="<?php echo get_permalink(get_page_by_path('checkout')); ?>" class="button checkout wc-forward"><?php _e('Checkout', 'qhshop'); ?></a>

        <p></p>


    </div>
</aside>