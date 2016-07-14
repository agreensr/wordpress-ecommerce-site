<?php 
//Shortcode for Testimonial Posts
function kad_testimonial_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'orderby' => '',
		'order' => '',
		'cat' => '',
		'id' => (rand(10,100)),
		'columns' => '',
		'limit_text' => false,
		'offset' => null,
		'wordcount' => '25',
		'link' => false,
		'isostyle' => 'masonry',
		'linktext' => __('Read More', 'virtue'),
		'items' => '3'
), $atts));
	if(empty($orderby)) {
		$orderby = 'menu_order';
	}
	if(!empty($order) ) {
		$order = $order;
	} else if($orderby == 'menu_order') {
		$order = 'ASC';
	} else {
		$order = 'DESC';
	} 
	if(empty($cat)) {$cat = '';}
	if(empty($columns)) {$columns = '3';}
		if ($columns == '2') {
			$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12';
		} else if ($columns == '1') {
			$itemsize = 'tcol-md-12 tcol-sm-12 tcol-xs-12 tcol-ss-12';
		} else if ($columns == '3'){
			$itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';
		} else if ($columns == '6'){
			$itemsize = 'tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6';
		} else if ($columns == '5'){
			$itemsize = 'tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6';
		} else {
			$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12';
		}
		global $virtue_premium; 
		if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == 1) {
			$animate = 1;
		} else {
			$animate = 0;
		}
	ob_start(); ?>
	<div class="home-testimonial">
		<div id="testimonialwrapper-<?php echo esc_attr($id);?>" class="rowtight init-isotope" data-fade-in="<?php echo esc_attr($animate);?>" data-iso-selector=".t_item" data-iso-style="<?php echo esc_attr($isostyle);?>" data-iso-filter="false"> 
            <?php $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array(
				  	'orderby' 			=> $orderby,
				  	'order' 			=> $order,
				  	'offset' 			=> $offset,
				  	'post_type' 		=> 'testimonial',
				  	'testimonial-group'	=> $cat,
				  	'posts_per_page' 	=> $items,
				  	)
				  );
					$count =0;
					if ( $wp_query ) : 
					while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<div class="<?php echo esc_attr($itemsize);?> t_item">
                		<div class="grid_item testimonial_item kad_testimonial_fade_in kt_item_fade_in postclass">
                			<div class="testimonialbox">
								<?php global $post; if (has_post_thumbnail( $post->ID ) ) {
									$image_url = wp_get_attachment_image_src( 
									get_post_thumbnail_id( $post->ID ), 'full' ); 
									$thumbnailURL = $image_url[0]; 
									$image = aq_resize($thumbnailURL, 60, 60, true);
									if(empty($image)) { $image = $thumbnailURL; } ?>
									<div class="alignleft testimonialimg">
	                                       <img src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>" class="" style="display: block; max-width:60px;">
	                                </div>
                           				<?php $image = null; $thumbnailURL = null;?>
                                <?php } else { ?>
                                	<div class="alignleft testimonialimg">
                                		<i class="icon-users" style="font-size:60px"></i>
                                	</div>
                                <?php } ?>
			                            <?php 

			                            if($limit_text) {
                                			echo esc_attr(strip_tags(virtue_content($wordcount))); 
		                                } else {
					                         the_content(); 
					                     }
					                     if($link) {
					                     	echo '<a href="'.get_the_permalink().'" class="kadtestimoniallink">';
		                                    echo $linktext;
		                                    echo '</a>';
					                     }

			                     ?>
			                    </div>
			                    <div class="testimonialbottom">
			                    	<div class="lipbg kad-arrow-down"></div>
			                    	<p><strong><?php the_title();?></strong>
			                    		<?php $location = get_post_meta( $post->ID, '_kad_testimonial_location', true ); 
				      						if($location != '') { echo ' - ' . $location;}
				      						?>
	      						</p>
			                </div>
			            </div>
                	</div>
					<?php endwhile; else: ?>
					<li class="error-not-found"><?php _e('Sorry, no testimonial entries found.', 'virtue');?></li>
				<?php endif; ?>
                </div> <!-- testimonialwrapper -->
                    <?php $wp_query = null; wp_reset_query(); ?>
		</div><!-- /.home-testimonial -->
            		

	<?php  $output = ob_get_contents();
		ob_end_clean();
		wp_reset_postdata();
	return $output;
}