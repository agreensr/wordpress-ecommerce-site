<div id="blog_carousel_container" class="carousel_outerrim">
    <?php global $post, $virtue_premium; 
    $text = get_post_meta( $post->ID, '_kad_blog_carousel_title', true );
    if( !empty($text)) {
    	echo '<h3 class="title">'.$text.'</h3>';
    } else {
    	echo '<h3 class="title">';
        echo apply_filters( 'similarposts_title', __('Similar Posts', 'virtue') );
        echo ' </h3>';
    } ?>
    <div class="blog-carouselcase fredcarousel">
    	<?php 
    	if(isset($virtue_premium['post_carousel_columns']) ) {
      			$columns = $virtue_premium['post_carousel_columns'];
      		} else {
      			$columns = '3';
      		}
    	if ($columns == '4') {
    		$itemsize = 'tcol-md-3 tcol-sm-3 tcol-xs-4 tcol-ss-12';
    		if (kadence_display_sidebar()) {
	    		$catimgwidth = 240;
    			$catimgheight = 240;
	    	} else {
	    		$catimgwidth = 267;
	    		$catimgheight = 267;
	    	}
    		$md = 4;
    		$sm = 3;
    		$xs = 2;
    		$ss = 1; 
    	} else if($columns == '5') {
    		$itemsize = 'tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6';
    		if (kadence_display_sidebar()) {
    			$catimgwidth = 240;
    			$catimgheight = 240;
    		} else {
    			$catimgwidth = 240;
    			$catimgheight = 240;
    		}
    		$md = 5;
    		$sm = 4;
    		$xs = 3;
    		$ss = 2;
    	} else if($columns == '6') {
    		$itemsize = 'tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6';
    		if (kadence_display_sidebar()) {
    			$catimgwidth = 240;
    			$catimgheight = 240;
    		} else {
    			$catimgwidth = 240;
    			$catimgheight = 240;
    		}
    		$md = 6;
    		$sm = 4;
    		$xs = 3;
    		$ss = 2;
    	} else {
    		$itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';
    		if (kadence_display_sidebar()) {
    			$catimgwidth = 266;
    			$catimgheight = 266;
    		} else {
    			$catimgwidth = 366;
    			$catimgheight = 366;
    		}
    		$md = 3;
    		$sm = 3;
    		$xs = 2;
    		$ss = 1;
    	} ?>
		<div id="carouselcontainer-blog" class="rowtight fadein-carousel">
    		<div id="blog_carousel" class="blog_carousel initcaroufedsel clearfix" data-carousel-container="#carouselcontainer-blog" data-carousel-transition="400" data-carousel-scroll="1" data-carousel-auto="true" data-carousel-speed="9000" data-carousel-id="blog" data-carousel-md="<?php echo esc_attr($md);?>" data-carousel-sm="<?php echo esc_attr($sm);?>" data-carousel-xs="<?php echo esc_attr($xs);?>" data-carousel-ss="<?php echo esc_attr($ss);?>">
      		<?php if(isset($virtue_premium['blog_similar_random_order']) && $virtue_premium['blog_similar_random_order'] == 1) {
      			$oderby = 'rand';
      		} else {
      			$oderby = 'date';
      		}
      		$categories = get_the_category($post->ID);
			if ($categories) {
				$category_ids = array();
				foreach($categories as $individual_category){
					$category_ids[] = $individual_category->term_id;
				}
			}
				
			$temp = $wp_query; 
			$wp_query = null; 
			$wp_query = new WP_Query();
			$wp_query->query(array(
				'orderby' => $oderby,
				'category__in' => $category_ids,
				'post__not_in' => array($post->ID),
				'posts_per_page'=> 8
				)
			);
			if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
            <div class="<?php echo esc_attr($itemsize);?>">
                	<div <?php post_class('blog_item grid_item'); ?>>
						<?php if (has_post_thumbnail( $post->ID ) ) {
								$image_url = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'full' ); 
								$thumbnailURL = $image_url[0]; 
								$image = aq_resize($thumbnailURL, $catimgwidth, $catimgheight, true);
								if(empty($image)) {$image = $thumbnailURL;} 
							} else {
                               	$thumbnailURL = virtue_post_default_placeholder();
                                $image = aq_resize($thumbnailURL, $catimgwidth, $catimgheight, true); 
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
		                          		<p><?php echo strip_tags(virtue_excerpt(16)); ?></p>
		                        	</div>
                           		</a>
                </div>
            </div>
					<?php endwhile; else: ?>
					 
					<li class="error-not-found"><?php _e('Sorry, no blog entries found.', 'virtue');?></li>
						
				<?php endif; ?>	
                <?php 
					  $wp_query = null; 
					  $wp_query = $temp;  // Reset
					?>
                    <?php wp_reset_query(); ?>
													
	</div>
     		<div class="clearfix"></div>
	            <a id="prevport-blog" class="prev_carousel icon-arrow-left" href="#"></a>
				<a id="nextport-blog" class="next_carousel icon-arrow-right" href="#"></a>
            </div>
        </div>
</div><!-- Porfolio Container-->			