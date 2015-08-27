var qhObject = {
    qhAttrCount : 0
};
jQuery(document).ready(function() {
    
	jQuery('.qhshop-box-menu a').click(function(event) {
		event.preventDefault();
		var nameContent = jQuery(this).attr('href');
		var currentObj = jQuery('#qhshop-content-' + nameContent.replace('#', ''));
		jQuery('.qhshop-option').hide();
		currentObj.show();
	});

        jQuery('#qhAddAttr').click(function(event) {
            event.preventDefault();
            console.log('Add');
            var content = '<div class="qhshop-content-form-group">';
            content += ' <input name="product[attributes]['+ qhObject.qhAttrCount +'][name]" type="text" value="" class="small-text" placeholder="Name">';
            content += ' <input name="product[attributes]['+ qhObject.qhAttrCount +'][value]" type="text" value="" class="small-text" placeholder="Value">';
            content += '</div>';
            jQuery('#qhshop-content-attribute-form-group').append(content);
            qhObject.qhAttrCount++;
        });
        
        jQuery('.qhDeleteIcon').click(function(event) {
            event.preventDefault();
            jQuery(this).parent().remove();
        });
});