<?php
get_header();
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        if (have_posts()) {
            while(have_posts()) {
                the_post();
                $data = get_post_meta(get_the_ID(), 'product')[0];
                ?>
                <div id="container">
                    <article id="post-<?php the_ID(); ?>" class="<?php echo implode(' ', get_post_class()); ?>">

                        <div class="entry-content">
                            <div id="product-<?php the_ID(); ?>"
                                 class="<?php echo implode(' ', get_post_class()); ?>">


                                <span class="onsale"><?php _e('Sale!', 'qhshop') ?></span>

                                <div class="images">
                                    <?php
                                    if(has_post_thumbnail()) {
                                        echo get_the_post_thumbnail();
                                    } else {
                                        printf('<img src="%s" alt="%s" />', QH_SHOP_PLUGIN_URL . 'images/placeholder.png', __('No Image', 'qhshop'));
                                    }
                                    ?>
                                </div>

                                <div class="summary entry-summary">

                                    <h1 itemprop="name" class="product_title entry-title"><?php the_title(); ?></h1>

                                    <div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                                        <?php
                                        $status = array(
                                            '0' => __('Out of stock', 'qhshop'),
                                            '1' => __('In stock', 'qhshop'),
                                            '2' => __('Call', 'qhshop'),
                                        );
                                        ?>
                                        <p><?php printf(__('Status: %s', 'qhshop'), ($data['product_status'] != 2 ? $status[$data['product_status']] : '<em style="color:red">'.$status[$data['product_status']].'</em>' )); ?></p>

                                        <p class="price">
                                            <del><span class="amount"><?php echo QHShopTool::get_currency($data['regular_price'],$option) ?></span></del>
                                            <ins><span class="amount"><?php echo QHShopTool::get_currency($data['sale_price'], $option); ?> VND</span></ins>
                                        </p>
                                    </div>
                                    <p class="stock in-stock"><?php printf(_n('1 in stock', '%s in stocks', $data['product_quantity'] ,'qhshop'), $data['product_quantity']) ?></p>

                                    <form class="cart" method="post" enctype="multipart/form-data"
                                          action="<?php echo get_permalink(); ?>">

                                        <div class="quantity buttons_added"><input type="button" value="-" class="minus"><input
                                                type="number" step="1" min="1" name="quantity" value="1" title="Qty"
                                                class="input-text qty text" size="4"><input type="button" value="+"
                                                                                            class="plus"></div>
                                        <input type="hidden" name="add-to-cart" value="<?php echo get_the_ID(); ?>">

                                        <button type="submit" class="single_add_to_cart_button button alt add_to_cart_button" data-item-id="<?php the_ID(); ?>"><?php _e('Add to cart', 'qhshop') ?></button>

                                    </form>


                                </div>
                                <!-- .summary -->


                                <div class="woocommerce-tabs">
                                    <ul class="tabs">

                                        <li class="description_tab active">
                                            <a href="#tab-description"><?php _e('Description', 'qhshop') ?></a>
                                        </li>

                                        <li class="additional_information_tab">
                                            <a href="#tab-additional_information"><?php _e('Additional Information', 'qhshop') ?></a>
                                        </li>

                                    </ul>

                                    <div class="panel entry-content" id="tab-description" style="display: block;">

                                        <h2><?php _e('Product Description', 'qhshop'); ?></h2>

                                        <?php echo the_content(); ?>
                                    </div>

                                    <div class="panel entry-content" id="tab-additional_information" style="display: none;">

                                        <h2><?php _e('Additional Information', 'qhshop'); ?></h2>

                                        <table class="shop_attributes">


                                            <tbody>
                                            <?php
                                                if (isset($data['attributes']) && !empty($data['attributes'])) {
                                                    foreach ($data['attributes'] as $attribute) {
                                                        ?>
                                                        <tr>
                                                            <th><?php echo $attribute['name']; ?></th>
                                                            <td><?php echo $attribute['value']; ?></td>
                                                        </tr>
                                            <?php
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="2">Updating....</td></tr>';
                                                }
                                            ?>

                                            </tbody>
                                        </table>
                                    </div>


                                </div>

                                <div class="tag">
                                    Tags:
                                    <?php
                                    $tags = get_the_terms(get_the_ID(), 'product_tags');
                                    if (!empty($tags)) {
                                        _e('Tags: ', 'qhshop');
                                        $tags_arr = array();
                                        foreach ($tags as $tag) {
                                            $tags_arr[] = sprintf('<a href="%s">%s</a>', esc_attr(get_term_link($tag->term_id, 'product_tags')), $tag->name);
                                        }
                                        echo implode(', ', $tags_arr);
                                    }
                                    ?>
                                    </div>
                            </div>
                        </div>
                        <!-- .entry-content -->

                        <footer class="entry-footer">
                            <?php
                            the_time('F j, Y ');
                            ?> <?php the_time('g:i:a'); ?>
                            <?php
                            if (is_user_logged_in() && current_user_can('edit_post')) {
                            ?>
                                <span class="edit-link"><a class="post-edit-link" href="<?php echo get_edit_post_link(); ?>"><?php _e('Edit', 'qhshop'); ?></a></span>
                            <?php
                            }
                            ?>
                        </footer>
                        <!-- .entry-footer -->
                    </article>
                    <!-- #post-## -->
                </div>
        <?php
                if (comments_open()) {
                    comments_template();
                }
            }
            wp_reset_postdata();
        }
        ?>
    </main>
    <!-- .site-main -->
</div>
<?php
get_footer();
?>
