<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop, $virtue_premium;

if ( empty( $product ) || ! $product->exists() ) {
	return;
}

if(!empty($virtue_premium['related_item_column'])) {$product_related_column = $virtue_premium['related_item_column'];} else {$product_related_column = '4';}
$woocommerce_loop['columns'] = $product_related_column;
						
						$rpc = array();
						if ($product_related_column == '2') {
							$rpc['md'] = 2; 
							$rpc['sm'] = 2; 
							$rpc['xs'] = 1;
							$rpc['ss'] = 1; 
						} else if ($product_related_column == '3'){
							$rpc['md'] = 3; 
							$rpc['sm'] = 3; 
							$rpc['xs'] = 2;
							$rpc['ss'] = 1; 
						} else if ($product_related_column == '6'){
							$rpc['md'] = 6; 
							$rpc['sm'] = 4; 
							$rpc['xs'] = 3;
							$rpc['ss'] = 2; 
						} else if ($product_related_column == '5'){ 
							$rpc['md'] = 5; 
							$rpc['sm'] = 4; 
							$rpc['xs'] = 3;
							$rpc['ss'] = 2; 
						} else {
							$rpc['md'] = 4; 
							$rpc['sm'] = 3; 
							$rpc['xs'] = 2;
							$rpc['ss'] = 1; 
						} 

						$rpc = apply_filters('kt_related_products_columns', $rpc);

$related = $product->get_related($posts_per_page);

if ( sizeof( $related ) == 0 ) return;

$args = apply_filters('woocommerce_related_products_args', array(
	'post_type'				=> 'product',
	'ignore_sticky_posts'	=> 1,
	'no_found_rows' 		=> 1,
	'posts_per_page' 		=> $posts_per_page,
	'orderby' 				=> $orderby,
	'post__in' 				=> $related,
	'post__not_in'			=> array($product->id)
) );

if(!empty($virtue_premium['related_products_text'])) {
	$relatedtext = $virtue_premium['related_products_text'];
} else {
	$relatedtext = __( 'Related Products', 'virtue');
}

$products = new WP_Query( $args );


if ( $products->have_posts() ) : ?>

	<div class="related products carousel_outerrim">
		<h3><?php echo $relatedtext; ?></h3>
	<div class="fredcarousel">
		<div id="carouselcontainer" class="rowtight">
			<div id="related-product-carousel" class="products initcaroufedsel caroufedselclass clearfix" data-carousel-container="#carouselcontainer" data-carousel-transition="700" data-carousel-scroll="1" data-carousel-auto="true" data-carousel-speed="9000" data-carousel-id="related_product" data-carousel-md="<?php echo esc_attr($rpc['md']);?>" data-carousel-sm="<?php echo esc_attr($rpc['sm']);?>" data-carousel-xs="<?php echo esc_attr($rpc['xs']);?>" data-carousel-ss="<?php echo esc_attr($rpc['ss']);?>">

			<?php while ( $products->have_posts() ) : $products->the_post();

				 woocommerce_get_template_part( 'content', 'product' ); 

			endwhile; ?>

				</div>
			</div>
			<div class="clearfix"></div>
            <a id="prevport-related_product" class="prev_carousel icon-arrow-left" href="#"></a>
			<a id="nextport-related_product" class="next_carousel icon-arrow-right" href="#"></a>
		</div>
	</div>
<?php endif;

wp_reset_postdata();
