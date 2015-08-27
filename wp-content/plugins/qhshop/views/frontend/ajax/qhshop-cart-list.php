<?php
$total = 0;

if (!empty($cart)) {
    foreach ($cart as $id => $item) {
        $total += $item['item_quantity'] * $item['item_price'];
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
    echo '<span id="qhshop-cart-subtotal" data-value="'.QHShopTool::get_currency($total, $option).'"></span>';
}