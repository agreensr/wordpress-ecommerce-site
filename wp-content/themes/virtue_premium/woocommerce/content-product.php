<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop, $virtue_premium;


// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ){
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

$product_column = $woocommerce_loop['columns'];
	if ($product_column == '1') {$itemsize = 'tcol-md-12 tcol-sm-12 tcol-xs-12 tcol-ss-12'; $productimgwidth = 300;}
 	else if ($product_column == '2') {$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $productimgwidth = 300;} 
		                else if ($product_column == '3'){ $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $productimgwidth = 400;} 
		                else if ($product_column == '6'){ $itemsize = 'tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $productimgwidth = 240;} 
		                else if ($product_column == '5'){ $itemsize = 'tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $productimgwidth = 240;} 
		                else {$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $productimgwidth = 300;}

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 === ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 === $woocommerce_loop['columns'] ) {
	$classes[] = 'first';
}
if ( 0 === $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
	$classes[] = 'last';
}
if(isset($virtue_premium['shop_hide_action']) && $virtue_premium['shop_hide_action'] == 1) {
$classes[] = 'hidetheaction';
}
$classes[] = 'grid_item';
$classes[] = 'product_item';
$classes[] = 'clearfix';
$classes[] = 'kad_product_fade_in';
$classes[] = 'kt_item_fade_in';

$terms = get_the_terms( $post->ID, 'product_cat' );
if ( $terms && ! is_wp_error( $terms ) ) : 
	$links = array();
	foreach ( $terms as $term ) {$links[] = $term->slug;}
	$links = preg_replace("/[^a-zA-Z 0-9]+/", " ", $links);
	$links = str_replace(' ', '-', $links);	
	$tax = join( " ", $links );		
	else :	
	$tax = '';	
endif;
?>
<div class="<?php echo esc_attr($itemsize);?> <?php echo strtolower($tax);?> kad_product">
	<div <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>" class="product_item_link product_img_link">

		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' ); ?> 

			
             </a>
            <div class="details_product_item">
			<div class="product_details">
				<a href="<?php the_permalink(); ?>" class="product_item_link">
				<?php 
				/**
			 	* woocommerce_shop_loop_item_title hook
			 	*
			 	* @hooked woocommerce_template_loop_product_title - 10
			 	*/
				do_action( 'woocommerce_shop_loop_item_title' );
				?>
				</a>
				<?php if(isset($virtue_premium['shop_excerpt']) && $virtue_premium['shop_excerpt'] == 1) {
				} else { ?>
					<div class="product_excerpt">
						<?php global $post; 
						if ($post->post_excerpt){
							echo apply_filters( 'archive_woocommerce_short_description', $post->post_excerpt );
						} else {
							the_excerpt();
						} ?>
					</div>
				<?php } ?>
			</div>
		
		<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
		?>


	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
	</div>

</div>
</div>