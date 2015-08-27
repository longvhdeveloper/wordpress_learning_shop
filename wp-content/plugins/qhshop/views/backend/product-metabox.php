<?php
wp_nonce_field($post->ID, 'qh_product_security');
?>
<div class="qhshop-box-left">
    <ul class="qhshop-box-menu">
        <li><a href="#general">General</a></li>
        <li><a href="#attribute">Attributes</a></li>
    </ul>
</div>
<div class="qhshop-box-right">
    <div id="qhshop-content-general" class="qhshop-option">
        <div class="qhshop-content-form-group">
            <label>Regular Price: </label><input name="product[regular_price]" type="number" step="any" min="0" value="<?php echo QHShopTool::get_currency($data['regular_price'], $option); ?>" class="small-text"> VND</div>
        <div class="qhshop-content-form-group">
            <label>Sale Price: </label><input name="product[sale_price]" type="number" step="any" min="0" value="<?php echo QHShopTool::get_currency($data['sale_price'], $option); ?>" class="small-text">VND        </div>
        <div class="qhshop-content-form-group">
            <label>Quantity: </label><input name="product[product_quantity]" type="number" step="1" min="1" value="<?php echo esc_attr($data['product_quantity']) ?>" class="small-text">
        </div>
        <div class="qhshop-content-form-group">
            <label>Status: </label>
            <?php
            $select_arr = array(
                '0' => __('Out of Stock', 'qhshop'),
                '1' => __('In Stock', 'qhshop'),
                '2' => __('Call', 'qhshop'),
            );
            ?>
            <select name="product[product_status]">
                <?php
                foreach ($select_arr as $key => $value) {
                    if (isset($data['product_status']) && $key == $data['product_status']) {
                        printf('<option selected value="%s">%s</option>', $key, $value);
                    } else {
                        printf('<option value="%s">%s</option>', $key, $value);
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div id="qhshop-content-attribute" class="qhshop-option">
        <div id="qhshop-content-attribute-form-group">
            <?php
            if (isset($data['attributes']) && !empty($data['attributes'][0])) {
                $count = count($data['attributes']);
                foreach ($data['attributes'] as $key => $attribute) {
                    printf('<div class="qhshop-content-form-group">');
                    printf('<input name="product[attributes][%s][name]" type="text" value="%s" class="small-text" placeholder="%s" />', $key, $attribute['name'], __('Name', 'qhshop'));
                    printf('<input name="product[attributes][%s][value]" type="text" value="%s" class="small-text" placeholder="%s" />', $key, $attribute['value'], __('Value', 'qhshop'));
                    printf('<a href="javascript:void(0)" class="qhDeleteIcon"><img src="%s" alt="Delete icon" /></a>', QH_SHOP_PLUGIN_URL . 'images/del_icon.png');
                    printf('</div>');
                }
                printf('<script>qhObject.qhAttrCount = %s</script>', $count);
            }
            ?>
        </div>
        <div>
            <button class="button button-primary button-large" id="qhAddAttr" type="button">Add</button>
        </div>
    </div>
</div>
<div class="clear"></div>