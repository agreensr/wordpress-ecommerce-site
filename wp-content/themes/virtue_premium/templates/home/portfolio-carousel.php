<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $virtue_premium, $kt_portfolio_loop;

?>
<div class="home-portfolio home-margin carousel_outerrim home-padding kad-animation" data-animation="fade-in" data-delay="0">
		<?php if(!empty($virtue_premium['portfolio_title'])) {$porttitle = $virtue_premium['portfolio_title']; } else { $porttitle = __('Featured Projects', 'virtue'); } ?>
			<div class="clearfix">
				<h3 class="hometitle">
					<?php echo $porttitle; ?>
				</h3>
			</div>
		
		<?php 
		if(!empty($virtue_premium['home_portfolio_order'])) {$hp_orderby = $virtue_premium['home_portfolio_order'];} else {$hp_orderby = 'menu_order';}
		if($hp_orderby == 'menu_order') {$p_order = 'ASC';} else {$p_order = 'DESC';}
		if(!empty($virtue_premium['home_portfolio_carousel_count'])) {$hp_pcount = $virtue_premium['home_portfolio_carousel_count'];} else {$hp_pcount = '8';}
		if(!empty($virtue_premium['home_portfolio_carousel_speed'])) {$hport_speed = $virtue_premium['home_portfolio_carousel_speed'].'000';} else {$hport_speed = '9000';}
		if(isset($virtue_premium['home_portfolio_carousel_scroll']) && $virtue_premium['home_portfolio_carousel_scroll'] == 'all' ) {$hport_scroll = '';} else {$hport_scroll = '1';}
					if(!empty($virtue_premium['portfolio_type'])) {
							$port_cat = get_term_by ('id',$virtue_premium['portfolio_type'],'portfolio-type');
							$portfolio_category = $port_cat -> slug;
						} else {
							$portfolio_category = '';
						}
					if(isset($virtue_premium['portfolio_show_type']) && $virtue_premium['portfolio_show_type'] == 1) {
					$portfolio_show_types = 'true';
					} else {
						$portfolio_show_types = 'false';
					}
					if(isset($virtue_premium['portfolio_show_excerpt']) && $virtue_premium['portfolio_show_excerpt'] == 1) {$portfolio_item_excerpt = 'true';} else {$portfolio_item_excerpt = 'false';}
					if(!empty($virtue_premium['home_portfolio_carousel_column'])) {$portfolio_column = $virtue_premium['home_portfolio_carousel_column'];} else {$portfolio_column = 3;}
					
					$pc = array();
					if ($portfolio_column == '2') {
						$itemsize = 'tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; 
						$slidewidth = 559; $slideheight = 559;
						$pc['md'] = 2; 
						$pc['sm'] = 2; 
						$pc['xs'] = 1;
						$pc['ss'] = 1;
					} else if ($portfolio_column == '3'){
						$itemsize = 'tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; 
						$slidewidth = 366; $slideheight = 366; 
						$pc['md'] = 3; 
						$pc['sm'] = 3; 
						$pc['xs'] = 2;
						$pc['ss'] = 1;
					} else if ($portfolio_column == '6'){ 
						$itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; 
						$slidewidth = 240; $slideheight = 240; 
						$pc['md'] = 6; 
						$pc['sm'] = 4; 
						$pc['xs'] = 3;
						$pc['ss'] = 2;
					} else if ($portfolio_column == '5'){
						$itemsize = 'tcol-lg-25 tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; 
						$slidewidth = 240; $slideheight = 240;
						$pc['md'] = 5; 
						$pc['sm'] = 4; 
						$pc['xs'] = 3;
						$pc['ss'] = 2;
					} else {
						$itemsize = 'tcol-lg-3 tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; 
						$slidewidth = 269; $slideheight = 269; 
						$pc['md'] = 4; 
						$pc['sm'] = 3; 
						$pc['xs'] = 2;
						$pc['ss'] = 1;
					} 
		            if(!empty($virtue_premium['home_portfolio_carousel_height'])) {$slideheight = $virtue_premium['home_portfolio_carousel_height'];}
		            $pc = apply_filters('kt_home_portfolio_carousel_columns', $pc);
		             $kt_portfolio_loop = array(
                 	'lightbox' => false,
                 	'showexcerpt' => $portfolio_item_excerpt,
                 	'showtypes' => $portfolio_show_types,
                 	'slidewidth' => $slidewidth,
                 	'slideheight' => $slideheight,
                 	);
		                ?>

	<div class="home-margin fredcarousel">
		<div id="hport_carouselcontainer" class="rowtight fadein-carousel">
			<div id="portfolio-carousel" class="clearfix caroufedselclass initcaroufedsel" data-carousel-container="#hport_carouselcontainer" data-carousel-transition="700" data-carousel-scroll="<?php echo esc_attr($hport_scroll);?>" data-carousel-auto="true" data-carousel-speed="<?php echo esc_attr($hport_speed);?>" data-carousel-id="hportfolio_carousel" data-carousel-md="<?php echo esc_attr($pc['md']);?>" data-carousel-sm="<?php echo esc_attr($pc['sm']);?>" data-carousel-xs="<?php echo esc_attr($pc['xs']);?>" data-carousel-ss="<?php echo esc_attr($pc['ss']);?>">
				<?php 
				$temp = $wp_query; 
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array(
					'orderby' => $hp_orderby,
					'order' => $p_order,
					'post_type' => 'portfolio',
					'portfolio-type'=>$portfolio_category,
					'posts_per_page' => $hp_pcount));
					$count =0;
					?>
					<?php if ( $wp_query ) : 
							 
					while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<div class="<?php echo esc_attr($itemsize); ?> kad_portfolio_item">
						<?php do_action('kadence_portfolio_loop_start');
								get_template_part('templates/content', 'loop-portfolio'); 
						  		do_action('kadence_portfolio_loop_end');
							?>
            		</div>
                    
					<?php endwhile; else: ?>
					<li class="error-not-found"><?php _e('Sorry, no portfolio entries found.', 'virtue');?></li>
						
				<?php endif; ?>
                                    
                    <?php 
                      $wp_query = null; 
                      $wp_query = $temp;  // Reset
                    ?>
                    <?php wp_reset_query(); ?>
                </div>
            </div>
			<div class="clearfix"></div>
            <a id="prevport-hportfolio_carousel" class="prev_carousel icon-arrow-left" href="#"></a>
			<a id="nextport-hportfolio_carousel" class="next_carousel icon-arrow-right" href="#"></a>
	</div> <!-- fred Carousel-->
</div> <!--featclass -->			