/*
 * kt variations plugin
 */

jQuery(document).ready(function ($) {

	var $vform = $('.product form.variations_form');
	var $vform_select = $('.variations td.product_value select');
	var $variations = $vform.find( '.single_variation_wrap' );
	var $use_ajax = $vform.data( 'product_variations' ) === false;


		$vform.on( 'click', '.reset_variations', function() {
			$('.variations .select2-container').select2("val", "");
			return false;
		} );
		$vform.on( 'reset_data', function() {
			$vform.find( '.single_variation_wrap_kad' ).find('.quantity').hide();
			$vform.find( '.single_variation .price').hide();
			//$vform.find( '.single_variation_wrap' ).css("height", "auto");
		} );

		$vform.on('woocommerce_variation_has_changed', function() {
			$('.kad-select').trigger('update');
		} );

		$variations.on('hide_variation', function() {
			$(this).css('height', 'auto');
		} );
		// Upon gaining focus
		$vform_select.on( 'select2-opening', function() {
			//$( this ).find( 'option:selected' ).attr( 'selected', 'selected' );
			if ( ! $use_ajax ) {
				$vform.trigger( 'woocommerce_variation_select_focusin' );
				$vform.trigger( 'check_variations', [ $( this ).data( 'attribute_name' ) || $( this ).attr( 'name' ), true ] );
			}
		} );
});

