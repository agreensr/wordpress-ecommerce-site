<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	global $virtue_premium;

	if(!empty($virtue_premium['product_best_title'])) {
		$product_best_title = $virtue_premium['product_best_title'];
	} else {
		$product_best_title = 'Best Selling Products';
	}
	if(!empty($virtue_premium['home_product_best_column'])) {
		$product_tcolumn = $virtue_premium['home_product_best_column'];
	} else {
		$product_tcolumn = '4';
	}
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
	$pc = apply_filters('kt_home_best_product_carousel_columns', $pc);
	if(!empty($virtue_premium['home_product_best_count'])) {
		$hp_probcount = $virtue_premium['home_product_best_count'];
	} else {
		$hp_probcount = '6';
	}
	if(!empty($virtue_premium['home_product_best_speed'])) {
		$hp_bestspeed = $virtue_premium['home_product_best_speed'].'000';
	} else {
		$hp_bestspeed = '9000';
	}
	if(isset($virtue_premium['home_product_best_scroll']) && $virtue_premium['home_product_best_scroll'] == 'all' ) {
		$hp_bestscroll = '';
	} else {
		$hp_bestscroll = '1';
	}?>
	<div class="home-product home-margin carousel_outerrim home-padding kad-animation" data-animation="fade-in" data-delay="0">
		<div class="clearfix">
			<h3 class="hometitle">
				<?php echo $product_best_title; ?>
			</h3>
		</div>
		
		<div class="fredcarousel">
			<div id="hpb_carouselcontainer" class="rowtight fadein-carousel">
				<div id="home-product-best-carousel" class="products caroufedselclass clearfix initcaroufedsel" data-carousel-container="#hpb_carouselcontainer" data-carousel-transition="700" data-carousel-scroll="<?php echo esc_attr($hp_bestscroll);?>" data-carousel-auto="true" data-carousel-speed="<?php echo esc_attr($hp_bestspeed);?>" data-carousel-id="hproductbest" data-carousel-md="<?php echo esc_attr($pc['md']);?>" data-carousel-sm="<?php echo esc_attr($pc['sm']);?>" data-carousel-xs="<?php echo esc_attr($pc['xs']);?>" data-carousel-ss="<?php echo esc_attr($pc['ss']);?>">
        		<?php global $woocommerce_loop;
        			$temp = $wp_query; 
				  	$wp_query = null; 
				  	$wp_query = new WP_Query();
				  	$wp_query->query(array(
						'post_type' => 'product',
						'meta_key'=> 'total_sales',
			            'orderby' => 'meta_value_num',
						'post_status' => 'publish',
						'posts_per_page' => $hp_probcount));
					$woocommerce_loop['columns'] = $product_tcolumn;
					
					if ( $wp_query ) : 
						while ( $wp_query->have_posts() ) : $wp_query->the_post();
							wc_get_template_part( 'content', 'product' ); 
						endwhile; 	
					endif; 
                      $wp_query = null; 
                      $wp_query = $temp;  // Reset
                    ?>
                    <?php wp_reset_query(); ?>
		</div>
		</div>
		<div class="clearfix"></div>
		            <a id="prevport-hproductbest" class="prev_carousel icon-arrow-left" href="#"></a>
					<a id="nextport-hproductbest" class="next_carousel icon-arrow-right" href="#"></a>
		</div>
	</div>