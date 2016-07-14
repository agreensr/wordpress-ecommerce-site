jQuery( function() {
	jQuery('.wc_extra_flat_rates .remove_tax_rates').click(function() {
		var $tbody = jQuery('.wc_extra_flat_rates').find('tbody');
		if ( $tbody.find('tr.current').size() > 0 ) {
			$current = $tbody.find('tr.current');
			$current.find('input').val('');
			$current.find('input.remove_flat_rate').val('1');

			$current.each(function(){
				if ( jQuery(this).is('.new') )
				jQuery(this).remove();
				else
				jQuery(this).hide();
			});
		} else {
			alert('No row(s) selected');
		}
		return false;
	});



	jQuery('.wc_extra_flat_rates .insert').click(function() {
		var $tbody = jQuery('.wc_extra_flat_rates').find('tbody');
		var size = $tbody.find('tr').size();

		var code = '<tr class="new">\
				<td width="4%" class="sort"></td>\
				<td class="name" width="48%">\
					<input type="text" class="" name="extra_flat_rate_name[new-' + size + ']" />\
				</td>\
				<td class="rate" width="48%">\
					<input type="number" class="wc_eft_valid_charge" step="any" min="0" placeholder="0" name="extra_flat_rate[new-' + size + ']" />\
				</td>\
			</tr>';

		if ( $tbody.find('tr.current').size() > 0 ) {
			$tbody.find('tr.current').after( code );
		} else {
			$tbody.append( code );
		}

		return false;
	});
	/*jQuery('body').on('keyup','.only_alphabetic',function(e) {

		var regex = /^[a-zA-Z 0-9 ]*$/;
		if ( !regex.test(jQuery(this).val()) ) {
			jQuery(this).val('');
		}
	});*/

	jQuery('body').on('keyup','.wc_eft_valid_charge',function(e) {
		if(e.keyCode != 9) {
			var phone = jQuery(this).val();
			var get_name = jQuery(this).attr('name');
			var get_name_value = '#'+get_name;
			intRegex = /[0-9 -()+]+$/;
			if(phone == 0 || phone < 0 ) {
				if(e.keyCode != 8) {
					var get_valid_charge = jQuery('#wc_eft_valid_charge_msg').html();
					alert( get_valid_charge);
					jQuery(this).val('');
					return false;
				}
				return false;
			} else {
				if(e.keyCode != 8) {
					if((!intRegex.test(phone))) {
						if((phone.length ==0 ) && (!intRegex.test(phone))) {
							var get_valid_charge = jQuery('#wc_eft_valid_charge_msg').html();
							alert( get_valid_charge);
							jQuery(this).val('');
							return false;
						}
					}
				}
				return false;
			}
		}
	});

	jQuery('body').on( 'click', '.woo-extra-flat-rate-notice .notice-dismiss', function() {
		
		jQuery.ajax({
			url: ajaxurl,
			data: {
				action: 'my_dismiss_flatrate_notice'
			}
		})

	});
});