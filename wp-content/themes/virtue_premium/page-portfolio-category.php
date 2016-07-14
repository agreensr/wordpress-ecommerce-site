	<?php
/*
Template Name: Portfolio Category Grid
*/
?>
	<div id="pageheader" class="titleclass">
		<div class="container">
			<?php get_template_part('templates/page', 'header'); ?>
		</div><!--container-->
	</div><!--titleclass-->
	
    <div id="content" class="container">
   		<div class="row">
      <div class="main <?php echo kadence_main_class(); ?>" id="ktmain" role="main">
      	  <?php if ( ! post_password_required() ) { ?>
			<div class="entry-content" itemprop="mainContentOfPage">
					<?php get_template_part('templates/content', 'page'); ?>
			</div>
      	<?php global $post, $virtue_premium; 
      	if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == 1) {$animate = 1;} else {$animate = 0;}
			   	$portfolio_items = get_post_meta( $post->ID, '_kad_portfolio_items', true );
							if($portfolio_items == 'all') { $portfolio_items = '-1'; }

                   global $post; $portfolio_column = get_post_meta( $post->ID, '_kad_portfolio_columns', true ); 
		                   if ($portfolio_column == '2') {$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $slidewidth = 560; $slideheight = 560;} 
		                   else if ($portfolio_column == '3'){ $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 366; $slideheight = 366;} 
		                   else if ($portfolio_column == '6'){ $itemsize = 'tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $slidewidth = 240; $slideheight = 240;} 
		                   else if ($portfolio_column == '5'){ $itemsize = 'tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $slidewidth = 240; $slideheight = 240;} 
		                   else {$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 270; $slideheight = 270;}
		            ?> 
                   <?php $portfolio_item_excerpt = get_post_meta( $post->ID, '_kad_portfolio_item_excerpt', true ); $portfolio_item_types = get_post_meta( $post->ID, '_kad_portfolio_item_types', true );  ?>
                   <?php $crop = true; ?>
                   <?php $portfolio_cropheight = get_post_meta( $post->ID, '_kad_portfolio_img_crop', true ); if ($portfolio_cropheight != '') $slideheight = $portfolio_cropheight; ?>
                   <?php $portfolio_crop = get_post_meta( $post->ID, '_kad_portfolio_crop', true ); if ($portfolio_crop == 'no') $slideheight = ''; $crop = false;?>
               <div id="portfoliowrapper" class="init-isotope rowtight" data-fade-in="<?php echo $animate;?>" data-iso-selector=".p-item" data-iso-style="masonry" data-iso-filter="false"> 
   
            <?php $meta = get_option('portfolio_cat_image');
									if (empty($meta)) $meta = array();
									if (!is_array($meta)) $meta = (array) $meta;
									$args = array( 'hide_empty=0' );
            $terms = get_terms("portfolio-type", $args);
					 if ( !empty( $terms ) && !is_wp_error( $terms ) ){
					     foreach ( $terms as $term ) { ?>
					     <div class="<?php echo $itemsize;?> p-item">
                			<div class="portfolio_item grid_item postclass kt_item_fade_in kad-light-gallery kad_portfolio_fade_in">
                				<?php $cat_term_id = $term -> term_id;
									if(isset($meta[$cat_term_id])) {$item_meta = $meta[$cat_term_id];} else {$item_meta = array();}
									if(isset($item_meta['category_image'])) { $bg_image_array = $item_meta['category_image']; $src = wp_get_attachment_image_src($bg_image_array[0], 'full'); $ct_image = $src[0];}
									if (!empty($ct_image)) {
									 $image = aq_resize($ct_image, $slidewidth, $slideheight, true);
									if(empty($image)) {$image = $ct_image;} ?>
										<div class="imghoverclass">
	                                       <a href="<?php echo get_term_link( $term );  ?>" title="<?php echo $term->name; ?>">
	                                       <img src="<?php echo $image ?>" alt="<?php echo $term->name; ?>" class="lightboxhover" style="display: block;">
	                                       </a> 
	                                	</div>
		                          <?php $ct_image = null; ?>
	                           <?php } ?>

					      	<?php echo '<a href="' . get_term_link( $term ) . '" class="portfoliolink">'; ?>

		              		<div class="piteminfo">   
		                          <h5><?php echo $term->name; ?></h5>
		                        <?php if($portfolio_item_excerpt == true) { ?> <p><?php echo $term -> description; ?></p> <?php } ?>
		                    </div>
		                </a>
		                </div>
                    </div>
					        
					    <?php }
					 } ?>
                </div> <!--portfoliowrapper-->
<?php global $virtue_premium; if(isset($virtue_premium['page_comments']) && $virtue_premium['page_comments'] == '1') { comments_template('/templates/comments.php');} ?>
<?php } else { ?>
      <?php echo get_the_password_form();
    }?>

</div><!-- /.main -->