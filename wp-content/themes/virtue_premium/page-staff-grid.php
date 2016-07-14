<?php
/*
Template Name: Staff Grid
*/
?>
	<div id="pageheader" class="titleclass">
		<div class="container">
			<?php get_template_part('templates/page', 'header'); ?>
		</div><!--container-->
	</div><!--titleclass-->
	
    <div id="content" class="container">
   		<div class="row">
      <div class="main <?php echo esc_attr(kadence_main_class()); ?>" id="ktmain" role="main">
			<div class="entry-content" itemprop="mainContentOfPage">
					<?php get_template_part('templates/content', 'page'); ?>
			</div>
      	<?php global $post, $virtue_premium; 
      		if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == 1) {
      			$animate = 1;
      		} else {
      			$animate = 0;
      		}
      		$staff_category 		= get_post_meta( $post->ID, '_kad_staff_type', true );
      		$staff_limit_content 	= get_post_meta( $post->ID, '_kad_staff_wordlimit', true );
      		$staff_single_link 		= get_post_meta( $post->ID, '_kad_staff_single_link', true );
      		$staff_items 			= get_post_meta( $post->ID, '_kad_staff_items', true );
      		$staff_column 			= get_post_meta( $post->ID, '_kad_staff_columns', true );
      		$staff_cropheight 		= get_post_meta( $post->ID, '_kad_staff_img_crop', true );
      		$staff_crop 			= get_post_meta( $post->ID, '_kad_staff_crop', true );
      		$staff_filter 			= get_post_meta( $post->ID, '_kad_staff_filter', true ); 
      		if(!empty($staff_limit_content)) {
      			$staff_limit_content = $staff_limit_content;
      		} else {
      			$staff_limit_content = 'false';
      		}
			if(!empty($staff_single_link)) {
				$staff_single_link = $staff_single_link;
			} else {
				$staff_single_link = 'false';
			}
			if($staff_category == '-1' || empty($staff_category)) { 
				$staff_cat_slug = ''; $staff_cat_ID = ''; 
			} else {
				$staff_cat 		= get_term_by ('id',$staff_category,'staff-group' );
				$staff_cat_slug = $staff_cat -> slug;
				$staff_cat_ID 	= $staff_cat -> term_id;
			}
			$staff_category = $staff_cat_slug;
			if($staff_items == 'all') {
				$staff_items = '-1';
			}
			if ($staff_column == '2') {
				$itemsize 	= 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12';
				$imgwidth 	= 560;
				$imgheight 	= 560; 
			} else if ($staff_column == '3'){
				$itemsize 	= 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';
				$imgwidth 	= 366;
				$imgheight 	= 366;
			} else if ($staff_column == '6'){
				$itemsize 	= 'tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6';
				$imgwidth 	= 240;
				$imgheight 	= 240;
			} else if ($staff_column == '5'){
				$itemsize 	= 'tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6';
				$imgwidth	= 240;
				$imgheight	= 240;
			} else {
				$itemsize 	= 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12';
				$imgwidth 	= 270;
				$imgheight 	= 270;
			}  
			$crop = true; 
            if (!empty($staff_cropheight)) {
            	$imgheight = $staff_cropheight;
            }
            if ($staff_crop == 'no') {
            	$imgheight = '';
            	$crop = false;
            } 
	  		if ($staff_filter == 'yes') {
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
            	$sft = "true";
            } ?>
               <div id="staffwrapper" class="rowtight init-isotope" data-fade-in="<?php echo esc_attr($animate);?>" data-iso-selector=".s_item" data-iso-style="masonry" data-iso-filter="<?php echo esc_attr($sft);?>"> 
            <?php $temp 	= $wp_query; 
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  	$wp_query->query(array(
						'paged' 		=> $paged,
						'post_type' 	=> 'staff',
						'orderby' 		=> 'menu_order',
						'order' 		=> 'ASC',
						'staff-group'	=> $staff_cat_slug,
						'posts_per_page'=> $staff_items));					
					if ( $wp_query ) : 	 
					while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
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
							<?php if (has_post_thumbnail( $post->ID ) ) {
									$image_url = wp_get_attachment_image_src( 
									get_post_thumbnail_id( $post->ID ), 'full' ); 
									$thumbnailURL = $image_url[0]; 
									if ($crop = true) { $image = aq_resize($thumbnailURL, $imgwidth, $imgheight, true);
										if(empty($image)) {$image = $thumbnailURL;}  
									} else { $image = aq_resize($thumbnailURL, $imgwidth, $imgheight, false); 
										if(empty($image)) {$image = $thumbnailURL;} }?>

									<div class="imghoverclass">
										<?php if($staff_single_link == "true") {?><a href="<?php the_permalink(); ?>"> <?php } else if($staff_single_link == "lightbox") {?><a href="<?php echo esc_url($thumbnailURL); ?>" data-rel="lightbox"  class="lightboxhover"> <?php }?>
	                                       <img src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>" class="" style="display: block;">
	                                   <?php if($staff_single_link == "true" || $staff_single_link == "lightbox") {?></a> <?php } ?>
	                                </div>
                           				<?php $image = null; $thumbnailURL = null;?>
                            <?php } ?>
			             <div class="staff_item_info">   
			                <?php if($staff_single_link == "true") {?><a href="<?php the_permalink(); ?>"> <?php }?>
                              <h3><?php the_title();?></h3>
                              <?php if($staff_single_link == "true") {?></a> <?php } ?>
			                 <?php if($staff_limit_content == "true") {
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
                </div> <!--portfoliowrapper-->
                                    
                    <?php if ($wp_query->max_num_pages > 1) : ?>
                            <?php kad_wp_pagenavi(); ?>   
                    <?php endif; ?>
                    <?php 
                      $wp_query = null; 
                      $wp_query = $temp;  // Reset
                    ?>
                    <?php wp_reset_query(); ?>
                    <?php global $virtue_premium; if(isset($virtue_premium['page_comments']) && $virtue_premium['page_comments'] == '1') { comments_template('/templates/comments.php');} ?>
</div><!-- /.main -->