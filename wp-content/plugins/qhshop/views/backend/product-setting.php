<div class="wrap">
<h2>Settings</h2>
    <?php
    if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
    ?>
        <div id="setting-error-settings_updated" class="updated setting-errors">
            <p><strong>Settings saved.</strong></p>
        </div>
    <?php
    }
    ?>
    <form name="post" action="options.php" method="post" id="post" autocomplete="off">
    <input type="hidden" name="option_page" value="<?php echo $option_group; ?>">
    <input type="hidden" name="action" value="update">
    <?php
    wp_nonce_field($option_group . '-options');
    ?>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">


            <div id="postbox-container-2" class="postbox-container">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                    <div id="product_detail" class="postbox ">
                        <div class="inside">
                            <div class="qhshop-box-left">
                                <ul class="qhshop-box-menu">
                                    <li><a href="#general">General</a></li>
                                    <li><a href="#currency">Currency</a></li>
                                    <li><a href="#pagemaker">Page Maker</a></li>
                                </ul>
                            </div>
                            <div class="qhshop-box-right">
                                <div id="qhshop-content-general" class="qhshop-option" style="display: block;">
                                    <div class="qhshop-content-form-group">
                                        <label>Items Per Page: </label>
                                        <input name="qhshop_setting[items_per_page]" type="number" step="1" min="1" value="<?php echo isset($option['items_per_page']) ? $option['items_per_page'] : 10 ?>" class="small-text">
                                    </div>
                                </div>
                                <div id="qhshop-content-currency" class="qhshop-option" style="display: none;">
                                    <div class="qhshop-content-form-group">
                                        <label>Currency Symbol: </label>
                                        <input name="qhshop_setting[currency_symbol]" type="text" value="<?php echo isset($option['currency_symbol']) ? $option['currency_symbol'] : 'VND' ?>" class="small-text">
                                    </div>
                                    <div class="qhshop-content-form-group">
                                        <label>Currency Position: </label>
                                        <select name="qhshop_setting[currency_position]">
                                            <?php
                                            $select_arr = array(
                                                '0' => __('Left', 'qhshop'),
                                                '1' => __('Right', 'qhshop')
                                            );
                                            foreach ($select_arr as $key => $value) {
                                                if ($key == $option['currency_position']) {
                                                    printf('<option value="%s" selected>%s</option>', $key, $value);
                                                } else {
                                                    printf('<option value="%s">%s</option>', $key, $value);
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="qhshop-content-form-group">
                                        <label>Thousand Separator: </label>
                                        <input name="qhshop_setting[thousand_separator]" type="text" value="<?php echo isset($option['thousand_separator']) ? $option['thousand_separator'] : ',' ?>" class="small-text">
                                    </div>
                                    <div class="qhshop-content-form-group">
                                        <label>Decimal Separator: </label>
                                        <input name="qhshop_setting[decimal_separator]" type="text" value="<?php echo isset($option['decimal_separator']) ? $option['decimal_separator'] : '.' ?>" class="small-text">
                                    </div>
                                    <div class="qhshop-content-form-group">
                                        <label>Number of Decimals: </label>
                                        <input name="qhshop_setting[number_of_decimals]" type="number" step="1" min="1" value="<?php echo isset($option['number_of_decimals']) ? $option['number_of_decimals'] : '2' ?>" class="small-text">
                                    </div>
                                </div>
                                <div id="qhshop-content-pagemaker" class="qhshop-option" style="display: none;">
                                    <div class="qhshop-content-form-group">
                                        <label>Shop Page: </label>
                                        <button type="submit" name="pagemaker[shop]" class="button">Create</button>
                                    </div>
                                    <div class="qhshop-content-form-group">
                                        <label>Checkout Page: </label>
                                        <button type="submit" name="pagemaker[checkout]" class="button">Create</button>
                                    </div>
                                </div>
                                <div class="alignright">
                                    <button class="button button-primary button-large" id="btnUpdate" type="submit">Save Changes</button>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /post-body -->
    <br class="clear">
    </div><!-- /poststuff -->
</form>
<div class="clear"></div></div>