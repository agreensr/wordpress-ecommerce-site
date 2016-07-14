<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	global $virtue_premium; 
	

	if(!empty($virtue_premium['product_sale_title'])) {$product_sale_title = $virtue_premium['product_sale_title'];} else {$product_sale_title = 'Products on Sale';}
	if(!empty($virtue_premium['home_product_sale_column'])) {$product_tcolumn = $virtue_premium['home_product_sale_column'];} else {$product_tcolumn = '4';}
	$pc = array();
	if ($product_tcolumn == '2') {
			$pc['md'] = 2; 
			$pc['sm'] = 2; 
			$pc['xs'] = 1;
			$pc['ss'] = 1; 
	} else if ($product_tcolumn == '3'){
			$pc['md'] = 3; 
			$pc['sm'] = 3; 
			$pc['xs'] = 2;
			$pc['ss'] = 1; 
	} else if ($product_tcolumn == '6'){
			$pc['md'] = 6; 
			$pc['sm'] = 4; 
			$pc['xs'] = 3;
			$pc['ss'] = 2; 
	} else if ($product_tcolumn == '5'){ 
			$pc['md'] = 5; 
			$pc['sm'] = 4; 
			$pc['xs'] = 3;
			$pc['ss'] = 2; 
	} else {
			$pc['md'] = 4; 
			$pc['sm'] = 3; 
			$pc['xs'] = 2;
			$pc['ss'] = 1; 
	} 
	$pc = apply_filters('kt_home_sale_product_carousel_columns', $pc);
	if(!empty($virtue_premium['home_product_sale_count'])) {$hp_proscount = $virtue_premium['home_product_sale_count'];} else {$hp_proscount = '6';}
	if(!empty($virtue_premium['home_product_sale_speed'])) {$hp_salespeed = $virtue_premium['home_product_sale_speed'].'000';} else {$hp_salespeed = '9000';} 
	if(isset($virtue_premium['home_product_sale_scroll']) && $virtue_premium['home_product_sale_scroll'] == 'all' ) {$hp_salescroll = '';} else {$hp_salescroll = '1';}?>
	<div class="home-product home-margin carousel_outerrim home-padding kad-animation" data-animation="fade-in" data-delay="0">
		<div class="clearfix">
			<h3 class="hometitle">
				<?php echo $product_sale_title; ?>
			</h3>
		</div>
		
		<div class="fredcarousel">
			<div id="hps_carouselcontainer" class="rowtight fadein-carousel">
				<div id="home-product-sale-carousel" class="products caroufedselclass clearfix initcaroufedsel" data-carousel-container="#hps_carouselcontainer" data-carousel-transition="700" data-carousel-scroll="<?php echo esc_attr($hp_salescroll);?>" data-carousel-auto="true" data-carousel-speed="<?php echo esc_attr($hp_salespeed);?>" data-carousel-id="hproductsale" data-carousel-md="<?php echo esc_attr($pc['md']);?>" data-carousel-sm="<?php echo esc_attr($pc['sm']);?>" data-carousel-xs="<?php echo esc_attr($pc['xs']);?>" data-carousel-ss="<?php echo esc_attr($pc['ss']);?>">
				<?php 
				global $woocommerce, $woocommerce_loop;
				$product_ids_on_sale = woocommerce_get_product_ids_on_sale(); $product_ids_on_sale[] = 0;
				$meta_query = array();
		        $meta_query[] = $woocommerce->query->visibility_meta_query();
		        $meta_query[] = $woocommerce->query->stock_status_meta_query();
					$temp = $wp_query; 
				  	$wp_query = null; 
				  	$wp_query = new WP_Query();
				  	$wp_query->query(array(
					'post_type' => 'product',
					'meta_query' => $meta_query,
 		            'post__in' => $product_ids_on_sale,
					'post_status' => 'publish',
					'orderby' => 'menu_order',
					'posts_per_page' => $hp_proscount));
					$woocommerce_loop['columns'] = $product_tcolumn;
					 if ( $wp_query ) :
					 	while ( $wp_query->have_posts() ) : $wp_query->the_post();
							wc_get_template_part( 'content', 'product' );
						endwhile; 
					endif;

                    $wp_query = null; 
                    $wp_query = $temp;  // Reset
                   	wp_reset_query(); ?>
				</div>
			</div>
			<div class="clearfix"></div>
		    <a id="prevport-hproductsale" class="prev_carousel icon-arrow-left" href="#"></a>
			<a id="nextport-hproductsale" class="next_carousel icon-arrow-right" href="#"></a>
		</div>
	</div>