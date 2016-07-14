<?php
/**
 * Product Loop End
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
global $woocommerce, $woocommerce_loop, $virtue_premium;
if(isset($virtue_premium['infinitescroll']) && $virtue_premium['infinitescroll'] == 1) {
	$infinitescroll = true;
} else {
	$infinitescroll = false;
}
?>
</div>
<script type="text/javascript">
	jQuery(document).ready(function ($) {	
		var $container = $('.kad_product_wrapper');
	<?php if ($infinitescroll) { ?>
		$('.kad_product_wrapper').infinitescroll({
		    nextSelector: ".woocommerce-pagination a.next",
		    navSelector: ".woocommerce-pagination",
		    itemSelector: ".kad_product",
		    loading: {
		    		msgText: "",
		            finishedMsg: '',
		            img: "<?php echo get_template_directory_uri() . '/assets/img/loader.gif'; ?>"
		        }
		    },
    	function( newElements ) {
         var $newElems = jQuery( newElements ).hide(); // hide to begin with
  			// ensure that images load before adding to masonry layout
		  $newElems.imagesLoadedn(function(){
		    $newElems.fadeIn(); // fade in when ready
		    $container.isotopeb( 'appended', $newElems );
		    if($container.attr('data-fade-in') == 1) {
						//fadeIn items one by one
						$newElems.each(function(i){
							$(this).find('.kad_product_fade_in').delay(i*150).animate({'opacity':1},350);
						});
					}
		  }); 

});	
<?php } ?>
});	
jQuery(document).on( "yith-wcan-ajax-filtered", function () {
	var $container = jQuery('.kad_product_wrapper');
						$container.imagesLoadedn( function(){
						$container.isotopeb({masonry: {columnWidth: '.kad_product'}, layoutMode:'fitRows', transitionDuration: '0.8s'});
						if($container.attr('data-fade-in') == 1) {
							jQuery('.kad_product_wrapper .kad_product_fade_in').css('opacity',0);
							jQuery('.kad_product_wrapper .kad_product_fade_in').each(function(i){
							jQuery(this).delay(i*150).animate({'opacity':1},350);});
						}
						});
						// update columnWidth on window resize
				

					jQuery('#filters').on( 'click', 'a', function( event ) {
					  var filtr = $(this).attr('data-filter');
					  $container.isotopeb({ filter: filtr });
					  return false; 
					});				
					var $optionSets = jQuery('#options .option-set'),$optionLinks = $optionSets.find('a');$optionLinks.click(function(){var $this = jQuery(this);if ( $this.hasClass('selected') ) {return false;}
					var $optionSet = $this.parents('.option-set');$optionSet.find('.selected').removeClass('selected');$this.addClass('selected');});
					});
				</script>