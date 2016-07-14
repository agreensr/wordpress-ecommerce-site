<?php
/*
Template Name: Blog
*/
?>

	<div id="pageheader" class="titleclass">
		<div class="container">
			<?php get_template_part('templates/page', 'header'); ?>
		</div><!--container-->
	</div><!--titleclass-->
	
    <div id="content" class="container">
   		<div class="row">
   			<?php global $post, $virtue_premium;
   			if(kadence_display_sidebar()) {
   				$display_sidebar = true;
   				$fullclass = '';
   			} else {
   				$display_sidebar = false;
   				$fullclass = 'fullwidth';
   			}
   			if(get_post_meta( $post->ID, '_kad_blog_summery', true ) == 'full') {
   				$summery = 'full';
   				$postclass = "single-article fullpost";
   			} else {
   				$summery = 'normal';
   				$postclass = 'postlist';
   			} ?>
      		<div class="main <?php echo esc_attr(kadence_main_class());?> <?php echo esc_attr($postclass) .' '. esc_attr($fullclass); ?>" id="ktmain" role="main">
      			<div class="entry-content" itemprop="mainContentOfPage">
					<?php get_template_part('templates/content', 'page'); ?>
				</div>
      			<?php $blog_category = get_post_meta( $post->ID, '_kad_blog_cat', true ); 
      			$blog_order = get_post_meta( $post->ID, '_kad_blog_order', true ); 
      			if(isset($virtue_premium['blog_infinitescroll']) && $virtue_premium['blog_infinitescroll'] == 1) {
      				$infinitescroll = true;
      			} else {
      				$infinitescroll = false;
      			}
					$blog_cat= get_term_by ('id',$blog_category,'category');
					if($blog_category == '-1' || $blog_category == '') {
      					$blog_cat_slug = '';
					} else {
					$blog_cat = get_term_by ('id',$blog_category,'category');
					$blog_cat_slug = $blog_cat -> slug;
					}

					$blog_items = get_post_meta( $post->ID, '_kad_blog_items', true ); 
					if($blog_items == 'all') {$blog_items = '-1';} 
					if(isset($blog_order)) {
			   			$b_orderby = $blog_order;
				   	} else {
				   		$b_orderby = 'date';
				   	}
				   	if($b_orderby == 'menu_order' || $b_orderby == 'title') {$b_order = 'ASC';} else {$b_order = 'DESC';}
					$temp = $wp_query; 
					$wp_query = null; 
					$wp_query = new WP_Query();
					$wp_query->query(array(
						'paged'		 	 => $paged,
						'orderby' => $b_orderby,
						'order' => $b_order,
						'category_name'	 => $blog_cat_slug,
						'posts_per_page' => $blog_items
						)
					);
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<?php if($summery == 'full') {
							if($display_sidebar){
								get_template_part('templates/content', 'fullpost'); 
							} else {
								get_template_part('templates/content', 'fullpostfull');
							}
						} else {
							if($display_sidebar){
						 	get_template_part('templates/content', get_post_format()); 
						 } else {
						 	get_template_part('templates/content', 'fullwidth');
						 }
						} 
                    endwhile; else: ?>
						<li class="error-not-found"><?php _e('Sorry, no blog entries found.', 'virtue'); ?></li>
					<?php endif; ?>
                
				<?php if ($wp_query->max_num_pages > 1) : ?>
        			<?php kad_wp_pagenavi(); ?>   
				<?php endif; ?>
				<?php $wp_query = null; $wp_query = $temp;  // Reset ?>
				<?php wp_reset_query(); ?>
				<?php if ($infinitescroll) { ?>
<script type="text/javascript">jQuery(document).ready(function ($) {
$('.main').infinitescroll({
    nextSelector: ".wp-pagenavi a.next",
    navSelector: ".wp-pagenavi",
    itemSelector: ".post",
    loading: {
    		msgText: "",
            finishedMsg: '',
            img: "<?php echo get_template_directory_uri() . '/assets/img/loader.gif'; ?>"
        }
    },
        function( newElements ) {
        	$(window).trigger( "infintescrollnewelements" );
         var $newElems = jQuery( newElements ); // hide to begin with
		    if($newElems.attr('data-animation') == 'fade-in') {
						//fadeIn items one by one
						$newElems.each(function() {
					    $(this).appear(function() {
					    $(this).delay($(this).attr('data-delay')).animate({'opacity' : 1, 'top' : 0},800,'swing');},{accX: 0, accY: -85},'easeInCubic');
					    });
					} 
    
		  }); 

});	
</script>
<?php } ?>
<?php global $virtue_premium; if(isset($virtue_premium['page_comments']) && $virtue_premium['page_comments'] == '1') { comments_template('/templates/comments.php');} ?>
</div><!-- /.main -->