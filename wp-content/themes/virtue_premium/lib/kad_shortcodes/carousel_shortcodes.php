<?php 
//Shortcode for Carousels
function kad_carousel_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'type' 					=> '',
		'id' 					=> (rand(10,100)),
		'columns' 				=> '4',
		'orderby' 				=> '',
		'order' 				=> '',
		'mcol' 					=> '',
		'scol' 					=> '',
		'xscol' 				=> '',
		'sscol' 				=> '',
		'img_height' 				=> '',
		'autoplay' 				=> 'true',
		'offset' 				=> null,
		'speed' 				=> '9000',
		'scroll' 				=> '',
		'portfolio_show_excerpt'  => 'false',
		'portfolio_show_types' 	  => 'false',
		'portfolio_show_lightbox' => 'false',
		'cat' 		=> '',
		'readmore' 	=> false,
		'items' 	=> '8'
), $atts));
	if(empty($type)) {
		$type = 'post';
	}
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
	if(!empty($cat)){
		$carousel_category = $cat;
	} else {
		$carousel_category = '';
	}
	if(empty($scroll) || $scroll == 1 ) {
		$scroll = '1';
	} else {
		$scroll = null;
	}
						if ($columns == '2') {
							$itemsize = 'tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12';
							$slidewidth = 560;
							$slideheight = 560;
							$md = 2;
							$sm = 2;
							$xs = 1;
							$ss = 1;
						} else if ($columns == '1') {
							$itemsize = 'tcol-lg-12 tcol-md-12 tcol-sm-12 tcol-xs-12 tcol-ss-12';
							$slidewidth = 560;
							$slideheight = 560;
							$md = 1;
							$sm = 1;
							$xs = 1;
							$ss = 1;
						} else if ($columns == '3'){
							$itemsize = 'tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';
							$slidewidth = 400;
							$slideheight = 400;
							$md = 3;
							$sm = 3;
							$xs = 2;
							$ss = 1;
						} else if ($columns == '8'){
							$itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6';
							$slidewidth = 200;
							$slideheight = 200;
							$md = 8;
							$sm = 6;
							$xs = 4;
							$ss = 2;
						} else if ($columns == '6'){
							$itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6';
							$slidewidth = 240;
							$slideheight = 240;
							$md = 6;
							$sm = 4;
							$xs = 3;
							$ss = 2;
						} else if ($columns == '5'){
							$itemsize = 'tcol-lg-25 tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6';
							$slidewidth = 240;
							$slideheight = 240;
							$md = 5;
							$sm = 4;
							$xs = 3;
							$ss = 2;
						} else {
							$itemsize = 'tcol-lg-3 tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12';
							$slidewidth = 300;
							$slideheight = 300;
							$md = 4;
							$sm = 3;
							$xs = 2;
							$ss = 1;
						} 
						if( !empty($mcol) ) {$md = $mcol;}
						if( !empty($scol) ) {$sm = $scol;}
						if( !empty($xscol) ) {$xs = $xscol;}
						if( !empty($sscol) ) {$ss = $sscol;}

ob_start(); ?>
	<div class="carousel_outerrim kad-animation" data-animation="fade-in" data-delay="0">
		<div class="home-margin carousel_outerrim_load fredcarousel">
			<div id="carouselcontainer-<?php echo esc_attr($id); ?>" class="rowtight fadein-carousel">
				<div id="carousel-<?php echo esc_attr($id); ?>" class="clearfix caroufedselclass products initcaroufedsel" data-carousel-container="#carouselcontainer-<?php echo esc_attr($id); ?>" data-carousel-transition="300" data-carousel-scroll="<?php echo esc_attr($scroll);?>" data-carousel-auto="<?php echo esc_attr($autoplay);?>" data-carousel-speed="<?php echo esc_attr($speed);?>" data-carousel-id="<?php echo esc_attr($id); ?>" data-carousel-md="<?php echo esc_attr($md);?>" data-carousel-sm="<?php echo esc_attr($sm);?>" data-carousel-xs="<?php echo esc_attr($xs);?>" data-carousel-ss="<?php echo esc_attr($ss);?>">
				
				<?php if ($type == "portfolio") { 
					if( !empty($img_height) ) {$slideheight = $img_height;}
				global $kt_portfolio_loop;
                 $kt_portfolio_loop = array(
                 	'lightbox' => $portfolio_show_lightbox,
                 	'showexcerpt' => $portfolio_show_excerpt,
                 	'showtypes' => $portfolio_show_types,
                 	'slidewidth' => $slidewidth,
                 	'slideheight' => $slideheight,
                 	);
				$wp_query = null; 
				$wp_query = new WP_Query();
						$wp_query->query(array(
							'orderby' 			=> $orderby,
							'order' 			=> $order,
							'offset' 			=> $offset,
							'post_type' 		=> 'portfolio',
							'portfolio-type'	=> $carousel_category,
							'posts_per_page' 	=> $items,
							)
						);
						if ( $wp_query ) :  while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
							<div class="<?php echo esc_attr($itemsize); ?> kad_product">
							<?php do_action('kadence_portfolio_loop_start');
								get_template_part('templates/content', 'loop-portfolio'); 
						  		do_action('kadence_portfolio_loop_end');
							?>
				            </div>
						<?php endwhile; else: ?>
							<li class="error-not-found"><?php _e('Sorry, no portfolio entries found.', 'virtue');?></li>
						<?php endif; $wp_query = null; wp_reset_query(); ?>

            	<?php } else if($type == "post") {
				$wp_query = null; 
				$wp_query = new WP_Query();
					$wp_query->query(array(
						'orderby' 			=> $orderby,
						'order' 			=> $order,
						'offset' 			=> $offset,
						'post_type' 		=> 'post',
						'category_name'		=> $carousel_category,
						'posts_per_page' 	=> $items,
						)
					);
					if ( $wp_query ) :  while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
						<div class="<?php echo esc_attr($itemsize);?> kad_product">
                			<div <?php global $post; post_class('blog_item grid_item'); ?>>
	                    		<?php if (has_post_thumbnail( $post->ID ) ) {
										$image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); 
										$thumbnailURL = $image_url[0]; 
										$image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true); 
										if(empty($image)) {$image = $thumbnailURL;}
									} else {
                               			$thumbnailURL = virtue_post_default_placeholder();
                                  		$image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true); 
                                  		if(empty($image)) { $image = $thumbnailURL; } 
                            		} ?>
									<div class="imghoverclass">
		                           		<a href="<?php the_permalink()  ?>" title="<?php the_title(); ?>">
		                           			<img src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>" class="iconhover" style="display:block;">
		                           		</a> 
		                         	</div>
                           			<?php $image = null; $thumbnailURL = null; ?>
              						<a href="<?php the_permalink() ?>" class="bcarousellink">
				                    	<header>
			                          		<h5 class="entry-title"><?php the_title(); ?></h5>
			                          		<div class="subhead">
			                          			<span class="postday kad-hidedate"><?php echo get_the_date('j M Y'); ?></span>
			                        		</div>	
			                        	</header>
		                        		<div class="entry-content color_body">
		                          			<p>
		                          				<?php echo strip_tags(virtue_excerpt(16)); ?><?php if($readmore) {global $virtue_premium; if(!empty($virtue_premium['post_readmore_text'])) {$readmoret = $virtue_premium['post_readmore_text'];} else {$readmoret = __('Read More', 'virtue');} echo $readmoret; }?>
		                          			</p>
		                        		</div>
                           			</a>
               		 		</div>
						</div>
					<?php endwhile; else: ?>
						<div class="error-not-found"><?php _e('Sorry, no post entries found.', 'virtue');?></div>
					<?php wp_reset_postdata(); 
					endif; 
					$wp_query = null; 
					 ?>								

            <?php } else if($type == "featured-products") {
				  global $woocommerce_loop;
				  	if($columns == 1) {
				  		$woocommerce_loop['columns'] = 3;
				  	}else {
				  		$woocommerce_loop['columns'] = $columns;
			    	}
				  $wp_query = null; 
				  $wp_query = new WP_Query();
					  $wp_query->query(array(
					  	'post_type' 		=> 'product',
					  	'meta_key' 			=> '_featured',
					  	'meta_value' 		=> 'yes',
					  	'post_status' 		=> 'publish',
					  	'offset' 			=> $offset,
					  	'orderby' 			=> 'menu_order',
					  	'posts_per_page' 	=> $items,
					  	)
					  );
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
						woocommerce_get_template_part( 'content', 'product' ); 
				 	endwhile; 
				 	endif;     
                    $wp_query = null;  wp_reset_query();

           	} else if($type == "sale-products") {
           			if (class_exists('woocommerce')) {
					  global $woocommerce, $woocommerce_loop;
						$product_ids_on_sale = woocommerce_get_product_ids_on_sale(); $product_ids_on_sale[] = 0;
						$meta_query = array();
			          $meta_query[] = $woocommerce->query->visibility_meta_query();
			          $meta_query[] = $woocommerce->query->stock_status_meta_query();
      				}
      				if($columns == 1) {
				  		$woocommerce_loop['columns'] = 3;
				  	}else {
				  		$woocommerce_loop['columns'] = $columns;
			    	}
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  	$wp_query->query(array(
				  		'post_type' 		=> 'product',
				  		'meta_query' 		=> $meta_query,
				  		'post__in' 			=> $product_ids_on_sale,
				  		'post_status' 		=> 'publish',
				  		'offset' 			=> $offset,
				  		'product_cat'		=> $carousel_category,
				  		'orderby' 			=> 'menu_order',
				  		'posts_per_page' 	=> $items,
				  		)
				  	);
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
						woocommerce_get_template_part( 'content', 'product' );
					endwhile;
					endif;         
                    $wp_query = null;  wp_reset_query();

           	} else if($type == "best-products") {
					  global $woocommerce_loop;
					if($columns == 1) {
				  		$woocommerce_loop['columns'] = 3;
				  	}else {
				  		$woocommerce_loop['columns'] = $columns;
			    	}
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  	$wp_query->query(array(
				  		'post_type' 		=> 'product',
				  		'meta_key'			=> 'total_sales',
				  		'orderby' 			=> 'meta_value_num',
				  		'post_status' 		=> 'publish',
				  		'offset' 			=> $offset,
				  		'posts_per_page' 	=> $items,
				  		)
				  	);
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
						woocommerce_get_template_part( 'content', 'product' ); 
					endwhile;
					endif;        
                    $wp_query = null;  wp_reset_query();

            } else if($type == "cat-products") {
					  global $woocommerce_loop;
					if($columns == 1) {
				  		$woocommerce_loop['columns'] = 3;
				  	}else {
				  		$woocommerce_loop['columns'] = $columns;
			    	}
				  $wp_query = null; 
				  $wp_query = new WP_Query();
					  $wp_query->query(array(
					  	'post_type' 		=> 'product',
					  	'orderby' 			=> $orderby,
					  	'order' 			=> $order,
					  	'offset' 			=> $offset,
					  	'product_cat'		=> $carousel_category,
					  	'post_status' 		=> 'publish',
					  	'posts_per_page' 	=> $items,
					  	)
					  );
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
						woocommerce_get_template_part( 'content', 'product' ); 
					endwhile; 
					endif;        
                    $wp_query = null;  wp_reset_query(); 
           	} ?>
           		</div>
			</div>
			<div class="clearfix"></div>
            <a id="prevport-<?php echo esc_attr($id); ?>" class="prev_carousel icon-arrow-left" href="#"></a>
			<a id="nextport-<?php echo esc_attr($id); ?>" class="next_carousel icon-arrow-right" href="#"></a>
		</div>
	</div>			

	<?php  $output = ob_get_contents();
		ob_end_clean();
		wp_reset_postdata();
	return $output;
}