<?php 
//Shortcode for staff Posts
function kad_staff_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'orderby' => '',
		'order' => '',
		'cat' => '',
		'offset' => null,
		'columns' => '3',
		'limit_content' => 'true',
		'lightbox' => 'true',
		'link' => 'false',
		'height' => '',
		'filter' => 'false',
		'id' => (rand(10,100)),
		'items' => '4'
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
	if(empty($cat)) {
		$cat = '';
		$staff_cat_ID = '';
	} else {
		$staff_cat 		= get_term_by ('slug',$cat,'staff-group' );
		$staff_cat_ID 	= $staff_cat -> term_id;
	}
	if ($columns == '2') {
		$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12';
		$imgwidth 	= 560;
		$imgheight 	= 560;
	} else if ($columns == '1') {
		$itemsize = 'tcol-md-12 tcol-sm-12 tcol-xs-12 tcol-ss-12';
		$imgwidth 	= 560;
		$imgheight 	= 560;
	} else if ($columns == '3'){
		$itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';
		$imgwidth 	= 366;
		$imgheight 	= 366;
	} else if ($columns == '6'){
		$itemsize = 'tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6';
		$imgwidth 	= 240;
		$imgheight 	= 240;
	} else if ($columns == '5'){
		$itemsize = 'tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6';
		$imgwidth 	= 240;
		$imgheight 	= 240;
	} else {
		$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12';
		$imgwidth = 270;
		$imgheight = 270;
	}
	if(!empty($height)) {
		$imgheight = $height;
	}
	global $virtue_premium;
	if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == 1) {
		$animate = 1;
	} else {
		$animate = 0;
	}
ob_start(); ?>
	<div class="home-staff">
	<?php if ($filter == "true") {
	  		$sft = "true"; ?>
      			<section id="options" class="clearfix">
			<?php 	if(!empty($virtue_premium['filter_all_text'])) {
						$alltext = $virtue_premium['filter_all_text'];
					} else {
						$alltext = __('All', 'virtue');
					}
					if(!empty($virtue_premium['portfolio_filter_text'])) {
						$stafffiltertext = $virtue_premium['portfolio_filter_text'];
					} else {
						$stafffiltertext = __('Filter Staff', 'virtue');
					}
					$termtypes  = array( 'child_of' => $staff_cat_ID,);
					$categories = get_terms('staff-group', $termtypes);
					$count      = count($categories);
						echo '<a class="filter-trigger headerfont" data-toggle="collapse" data-target=".filter-collapse"><i class="icon-tags"></i> '.$stafffiltertext.'</a>';
						echo '<ul id="filters" class="clearfix option-set filter-collapse">';
						echo '<li class="postclass"><a href="#" data-filter="*" title="All" class="selected"><h5>'.$alltext.'</h5><div class="arrow-up"></div></a></li>';
						 if ( $count > 0 ){
							foreach ($categories as $category){ 
							$termname = strtolower($category->name);
							$termname = preg_replace("/[^a-zA-Z 0-9]+/", " ", $termname);
							$termname = str_replace(' ', '-', $termname);
							echo '<li class="postclass"><a href="#" data-filter=".'.esc_attr($termname).'" title="" rel="'.esc_attr($termname).'"><h5>'.esc_html($category->name).'</h5><div class="arrow-up"></div></a></li>';
								}
				 		}
				 		echo "</ul>"; ?>
				</section>
            <?php } else {
            	$sft = "false";
            } ?>
		<div id="staffwrapper-<?php echo esc_attr($id);?>" class="rowtight init-isotope" data-fade-in="<?php echo esc_attr($animate);?>" data-iso-selector=".s_item" data-iso-style="masonry" data-iso-filter="<?php echo esc_attr($sft);?>"> 
            <?php $wp_query = null; 
				  $wp_query = new WP_Query();
					  $wp_query->query(array(
					  	'orderby' 			=> $orderby,
					  	'order' 			=> $order,
					  	'offset' 			=> $offset,
					  	'post_type' 		=> 'staff',
					  	'staff-group'		=> $cat,
					  	'posts_per_page' 	=> $items,
					  	)
					  );
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
					global $post; 
					$terms = get_the_terms( $post->ID, 'staff-group' );
						if ( $terms && ! is_wp_error( $terms ) ) : 
							$links = array();
							foreach ( $terms as $term ) { $links[] = $term->name;}
							$links = preg_replace("/[^a-zA-Z 0-9]+/", " ", $links);
							$links = str_replace(' ', '-', $links);	
							$tax = join( " ", $links );		
						else :	
							$tax = '';	
						endif; ?> 
					<div class="<?php echo esc_attr($itemsize);?> <?php echo strtolower($tax); ?> s_item">
                		<div class="grid_item staff_item kt_item_fade_in kad_staff_fade_in postclass">
							<?php
								if (has_post_thumbnail( $post->ID ) ) {
									$image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); 
									$thumbnailURL = $image_url[0]; 
									$image = aq_resize($thumbnailURL, $imgwidth, $imgheight, true, false);
									if(empty($image[0])) {$image[0] = $thumbnailURL; $image[1] = null; $image[2] = null;} 
									?>
									<div class="imghoverclass">
									<?php if($link == 'true') {?>
										<a href="<?php the_permalink(); ?>"> 
									<?php } else if($lightbox == 'true') {?>
										<a href="<?php echo esc_url($thumbnailURL); ?>" data-rel="lightbox[pp_gal]"  class="lightboxhover">
									<?php } ?>
	                                       <img src="<?php echo esc_url($image[0]); ?>" width="<?php echo esc_attr($image[1]); ?>" height="<?php echo esc_attr($image[2]); ?>" alt="<?php the_title(); ?>" class="" style="display: block;">
	                                <?php if($lightbox == 'true' || $link == 'true') {?>
	                                    </a> 
	                                <?php } ?>
	                                </div>
                           				<?php $image = null; $thumbnailURL = null;?>
                            <?php } ?>
			             <div class="staff_item_info">  
			             	<?php if($link == 'true') {?><a href="<?php the_permalink(); ?>"> <?php }?>
			                <h3><?php the_title();?></h3>
			                <?php if($link == 'true') {?></a> <?php } ?>
			                <?php if($limit_content == 'true') {
			                	 the_excerpt();
				                } else {
				                  the_content(); 
				                } ?>
			            </div>
                	</div>
                </div>
					<?php endwhile; else: ?>
					<li class="error-not-found"><?php _e('Sorry, no staff entries found.', 'virtue');?></li>
				<?php endif; ?>
                </div> <!-- staffwrapper -->
                    <?php $wp_query = null; wp_reset_query(); ?>
		</div><!-- /.home-staff -->
            		

	<?php  $output = ob_get_contents();
		ob_end_clean();
		wp_reset_postdata();
	return $output;
}