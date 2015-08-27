<?php
if (!empty($cart)) {
?>
<div class="woocommerce">
    <form name="checkout" method="post" class="checkout" action="<?php echo get_permalink(); ?>"
          enctype="multipart/form-data">

        <?php
        wp_nonce_field('qhshop_checkout');
        ?>

        <div class="col2-set" id="customer_details">

            <div class="col-1">

                <?php
                if (is_array($errors) && !empty($errors)) {
                ?>
                    <div class="woocommerce-error">
                        <?php
                        foreach ($errors as $error) {
                            echo $error . '<br/>';
                        }
                        ?>
                    </div>
                <?php
                }
                ?>

                <div class="woocommerce-billing-fields">

                    <h3>Billing Details</h3>


                    <p class="form-row form-row-first validate-required" id="billing_first_name_field">
                        <label for="billing_first_name" class="">First Name <abbr class="required"
                                                                                  title="required">*</abbr></label>
                        <input type="text" class="input-text " name="checkout[billing_first_name]"
                               id="billing_first_name" placeholder="Enter Your First Name" value="<?php echo (isset($_POST['checkout']['billing_first_name'])) ? esc_attr($_POST['checkout']['billing_first_name']) : '' ?>">
                    </p>

                    <p class="form-row form-row-last validate-required" id="billing_last_name_field">
                        <label for="billing_last_name" class="">Last Name <abbr class="required"
                                                                                title="required">*</abbr></label>
                        <input type="text" class="input-text " name="checkout[billing_last_name]" id="billing_last_name"
                               placeholder="Enter Your Last Name" value="<?php echo (isset($_POST['checkout']['billing_last_name'])) ? esc_attr($_POST['checkout']['billing_last_name']) : '' ?>">
                    </p>

                    <div class="clear"></div>

                    <p class="form-row form-row-wide address-field validate-required" id="billing_address_1_field">
                        <label for="billing_address" class="">Address <abbr class="required"
                                                                            title="required">*</abbr></label>
                        <input type="text" class="input-text " name="checkout[billing_address]" id="billing_address"
                               placeholder="Enter Your Address" value="<?php echo (isset($_POST['checkout']['billing_address'])) ? esc_attr($_POST['checkout']['billing_address']) : '' ?>">
                    </p>


                    <p class="form-row form-row-wide address-field validate-required" id="billing_city_field"
                       data-o_class="form-row form-row-wide address-field validate-required">
                        <label for="billing_city" class="">Town / City <abbr class="required" title="required">*</abbr></label>
                        <input type="text" class="input-text " name="checkout[billing_city]" id="billing_city"
                               placeholder="Town / City" value="<?php echo (isset($_POST['checkout']['billing_city'])) ? esc_attr($_POST['checkout']['billing_city']) : '' ?>">
                    </p>


                    <div class="clear"></div>

                    <p class="form-row form-row-first validate-required validate-email" id="billing_email_field">
                        <label for="billing_email" class="">Email Address <abbr class="required"
                                                                                title="required">*</abbr></label>
                        <input type="text" class="input-text " name="checkout[billing_email]" id="billing_email"
                               placeholder="Enter Your Email" value="<?php echo (isset($_POST['checkout']['billing_email'])) ? esc_attr($_POST['checkout']['billing_email']) : '' ?>">
                    </p>

                    <p class="form-row form-row-last validate-required validate-phone" id="billing_phone_field">
                        <label for="billing_phone" class="">Phone <abbr class="required"
                                                                        title="required">*</abbr></label>
                        <input type="text" class="input-text " name="checkout[billing_phone]" id="billing_phone"
                               placeholder="Enter Your Phone Number" value="<?php echo (isset($_POST['checkout']['billing_phone'])) ? esc_attr($_POST['checkout']['billing_phone']) : '' ?>">
                    </p>

                    <div class="clear"></div>


                </div>
            </div>

        </div>


        <h3 id="order_review_heading">Your order</h3>


        <div id="order_review" style="position: relative; zoom: 1;">
            <table class="shop_table">
                <thead>
                <tr>
                    <th class="product-name"><?php _e('Product', 'qhshop');?></th>
                    <th class="product-total"><?php _e('Total', 'qhshop') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $total = 0;
                foreach ($cart as $id => $item) {
                    $total += $item['item_price'] * $item['item_quantity'];
                ?>
                <tr class="cart_item">
                    <td class="product-name">
                        <?php echo esc_attr($item['item_name']) ?><strong class="product-quantity">X <?php echo $item['item_quantity'] ?></strong>
                    </td>
                    <td class="product-total">
                        <span class="amount"><?php echo QHShopTool::get_currency($item['item_price'], $option) ?></span>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
                <tfoot>


                <tr class="order-total">
                    <th><?php _e('Order Total', 'qhshop') ?></th>
                    <td><strong><span class="amount"><?php echo QHShopTool::get_currency($total, $option); ?></span></strong></td>
                </tr>


                </tfoot>
            </table>


            <div id="payment">

                <div class="form-row place-order">

                    <noscript>Since your browser does not support JavaScript, or it is disabled, please ensure you click
                        the &amp;lt;em&amp;gt;Update Totals&amp;lt;/em&amp;gt; button before placing your order. You may
                        be charged more than the amount stated above if you fail to do so.&amp;lt;br/&amp;gt;&amp;lt;input
                        type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="Update totals"
                        /&amp;gt;
                    </noscript>
                    <input type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order"
                           value="Place order" data-value="Place order">


                </div>

                <div class="clear"></div>

            </div>
        </div>
    </form>

</div>
<?php } else {
    echo __('Empty cart', 'qhshop');
} ?>