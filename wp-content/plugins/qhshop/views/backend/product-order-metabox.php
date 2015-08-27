<?php
wp_nonce_field($post->ID, 'qh_order_security');
?>
<div class="qhshop-box-left">
    <ul class="qhshop-box-menu">
        <li><a href="#general"><?php _e('General', 'qhshop'); ?></a></li>
        <li><a href="#billing"><?php _e('Billing Information', 'qhshop'); ?></a></li>
        <li><a href="#items"><?php _e('Items', 'qhshop') ?></a></li>
    </ul>
</div>
<div class="qhshop-box-right">
    <div id="qhshop-content-general" class="qhshop-option" style="display: none;">
        <div class="qhshop-content-form-group">
            <label><?php _e('Status', 'qhshop'); ?>: </label>
            <?php
            $select_arr = array(
                '0' => __('Pending', 'qhshop'),
                '1' => __('Processing', 'qhshop'),
                '2' => __('Completed', 'qhshop'),
            );
            ?>
            <select name="order[status]">
                <?php
                foreach ($select_arr as $key => $value) {
                    if (isset($data['status']) && $data['status'] == $key) {
                        printf('<option selected value="%s">%s</option>', $key, $value);
                    } else {
                        printf('<option value="%s">%s</option>', $key, $value);
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div id="qhshop-content-billing" class="qhshop-option" style="display: none;">
        <?php
        if (isset($data['checkout']) && !empty($data['checkout'])) {
            ?>
            <div id="qhshop-content-billing-form-group">
                <label><strong><?php _e('First Name:', 'qhshop'); ?></strong> <?php echo $data['checkout']['billing_first_name']; ?>
                </label>
            </div>
            <div id="qhshop-content-billing-form-group">
                <label><strong><?php _e('Last Name:', 'qhshop'); ?></strong> <?php echo $data['checkout']['billing_last_name']; ?>
                </label>
            </div>
            <div id="qhshop-content-billing-form-group">
                <label><strong><?php _e('Address:', 'qhshop') ?></strong> <?php echo $data['checkout']['billing_address']; ?>
                </label>
            </div>
            <div id="qhshop-content-billing-form-group">
                <label><strong><?php _e('Email:', 'qhshop') ?></strong> <?php echo $data['checkout']['billing_city']; ?>
                </label>
            </div>
            <div id="qhshop-content-billing-form-group">
                <label><strong><?php _e('Phone:', 'qhshop'); ?></strong> <?php echo $data['checkout']['billing_phone']; ?>
                </label>
            </div>
        <?php } ?>
    </div>
    <div id="qhshop-content-items" class="qhshop-option" style="">
        <div id="qhshop-content-items-form-group">
            <table class="wp-list-table widefat fixed posts">
                <thead>
                <tr>
                    <th><?php _e('Name'); ?></th>
                    <th><?php _e('Quantity'); ?></th>
                    <th><?php _e('Sale Price'); ?></th>
                </tr>
                </thead>
                <tbody id="the-list">
                <?php
                if (isset($data['cart']) && !empty($data['cart'])) {
                    foreach ($data['cart'] as $item) {
                        $total = $item['item_price'] * $item['item_quantity'];
                ?>
                        <tr>
                            <td><?php echo $item['item_name']; ?></td>
                            <td><?php echo $item['item_quantity']; ?></td>
                            <td><?php echo QHShopTool::get_currency($item['item_price'], $option); ?></td>
                        </tr>
                    <?php
                    }
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3"><?php _e('Total:', 'qhshop'); ?> <?php echo QHShopTool::get_currency($total, $option); ?></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="clear"></div>