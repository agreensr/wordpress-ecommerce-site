<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

	global $virtue_premium, $woocommerce_loop;


		$product_title = $virtue_premium['product_title'];
		if(!empty($product_title)) {
				$ptitle = $product_title;
		} else {
			$ptitle = 'Featured Products';
		}
		if(!empty($virtue_premium['home_product_feat_column'])) {
			$product_tcolumn = $virtue_premium['home_product_feat_column'];
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
		$pc = apply_filters('kt_home_featured_product_carousel_columns', $pc);
		if(!empty($virtue_premium['home_product_count'])) {
			$hp_procount = $virtue_premium['home_product_count'];
		} else {
			$hp_procount = '6';
		}
		if(!empty($virtue_premium['home_product_feat_speed'])) {
			$hp_featspeed = $virtue_premium['home_product_feat_speed'].'000';
		} else {
			$hp_featspeed = '9000';
		} 
		if(isset($virtue_premium['home_product_feat_scroll']) && $virtue_premium['home_product_feat_scroll'] == 'all' ) {
			$hp_featscroll = '';
		} else {
			$hp_featscroll = '1';
		}?>
	<div class="home-product home-margin carousel_outerrim home-padding kad-animation" data-animation="fade-in" data-delay="0">
		<div class="clearfix">
			<h3 class="hometitle">
				<?php echo $ptitle; ?>
			</h3>
		</div>

		<div class=" fredcarousel">
			<div id="hp_carouselcontainer" class="rowtight fadein-carousel">
				<div id="home-product-carousel" class="products caroufedselclass clearfix initcaroufedsel" data-carousel-container="#hp_carouselcontainer" data-carousel-transition="700" data-carousel-scroll="<?php echo esc_attr($hp_featscroll);?>" data-carousel-auto="true" data-carousel-speed="<?php echo esc_attr($hp_featspeed);?>" data-carousel-id="hfproduct" data-carousel-md="<?php echo esc_attr($pc['md']);?>" data-carousel-sm="<?php echo esc_attr($pc['sm']);?>" data-carousel-xs="<?php echo esc_attr($pc['xs']);?>" data-carousel-ss="<?php echo esc_attr($pc['ss']);?>">
				<?php 
		          $temp = $wp_query; 
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array(
					'post_type' => 'product',
					'meta_key' => '_featured',
					'meta_value' => 'yes',
					'post_status' => 'publish',
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'posts_per_page' => $hp_procount));
					$woocommerce_loop['columns'] = $product_tcolumn;
					if ( $wp_query ) :
						while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
							wc_get_template_part( 'content', 'product' ); 
						endwhile; 
					endif; ?>
				</div>
			</div>
			<div class="clearfix"></div>
            	<a id="prevport-hfproduct" class="prev_carousel icon-arrow-left" href="#"></a>
				<a id="nextport-hfproduct" class="next_carousel icon-arrow-right" href="#"></a>
		</div>
	</div>

	<?php 
        $wp_query = null; 
        $wp_query = $temp;  // Reset
    	wp_reset_query(); 
    	?>