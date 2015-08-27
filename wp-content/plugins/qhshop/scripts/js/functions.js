jQuery(document).ready(function() {
    //Tab
    jQuery('.woocommerce-tabs ul.tabs a').on('click', function(event) {
        event.preventDefault();
        var prevTab = jQuery('.woocommerce-tabs ul.tabs li.active a');
        var currTab = jQuery(this);
        var prevTabId = prevTab.attr('href');
        var currTabId = currTab.attr('href');
        jQuery(prevTab).parent().removeClass('active');
        jQuery(prevTabId).hide();
        jQuery(currTabId).show();
        jQuery(currTab).parent().addClass('active');
    });

    //Plus and Minus Button
    jQuery('.quantity .minus').on('click', function(event) {
        event.preventDefault();
        var currentValue = parseInt(jQuery(this).next().val());
        if (currentValue - 1 != 0) {
            jQuery(this).next().val(currentValue - 1);
        }
    });

    jQuery('.quantity .plus').on('click', function(event) {
        event.preventDefault();
        var currentValue = parseInt(jQuery(this).prev().val());
        jQuery(this).prev().val(currentValue + 1);
    });

    //Orderby Selection
    jQuery('.woocommerce-ordering .orderby').change(function(event) {
        var url = jQuery(this).val();
        window.location = url;
    });


    //Add to Cart AJAX
    jQuery('.add_to_cart_button').on('click', function(event) {
        event.preventDefault();
        //Change loading
        var currentButton = jQuery(this);
        jQuery('html').css('cursor', 'progress');
        currentButton.css('cursor', 'progress');

        var item_id = jQuery(this).attr('data-item-id');
        var item_quantity = jQuery(this).prevAll('.quantity').children().eq(1).val();
        if (typeof item_quantity == 'undefined') {
            item_quantity = 1;
        }
        jQuery.ajax({
            url: qhshop.url,
            type: "POST",
            data: {
                'action' : 'add-to-cart',
                'item_id' : item_id,
                'quantity' : item_quantity
            }
        }).done(function(result) {
            if (result.data.status) {
                jQuery('.qh_shopping_cart').show();
                jQuery('#qhshop-cart-list').html(result.data.cart);
                //Update Subtotal
                var subtotal = jQuery('#qhshop-cart-subtotal').attr('data-value');
                jQuery('.subtotal').html(subtotal);
                //Change Loading
                jQuery('html').css('cursor', 'auto');
                currentButton.css('cursor', 'pointer');
            }
        });
    });

    //Delete Cart
    jQuery(document).on('click', '.delete_button', function(event) {
        event.preventDefault();
        //Change Loading
        var currentButton = jQuery(this);
        jQuery('html').css('cursor', 'progress');
        currentButton.css('cursor', 'progress');


        var item_id = jQuery(this).attr('data-item-id');
        var currentButton = jQuery(this);
        jQuery.ajax({
            url: qhshop.url,
            type: "POST",
            data: {
                'action' : 'delete-cart',
                'item_id' : item_id
            }
        }).done(function(result) {
            if (result.data.status) {
                jQuery(currentButton).parent().remove();
                jQuery('.subtotal').html(result.data.subtotal);
                if (!jQuery('.delete_button').length) {
                    jQuery('.qh_shopping_cart').hide();
                }
                //Change Loading
                jQuery('html').css('cursor', 'auto');
                currentButton.css('cursor', 'pointer');
            }
        });
    });
});