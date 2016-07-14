  <?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $post;
?>
<div id="post-<?php the_ID(); ?>" class="blog_item search_results_item kt_item_fade_in kad_blog_fade_in grid_item">
    <?php if (has_post_thumbnail( $post->ID ) ) {$image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); 
        $thumbnailURL = $image_url[0];
        $image = aq_resize($thumbnailURL, 260, null, false, false);
        if(empty($image[0])) {$image[0] = $thumbnailURL; $image[1] = null; $image[2] = null;} ?>
          <div class="imghoverclass img-margin-center">
            <a href="<?php the_permalink()  ?>" title="<?php the_title(); ?>">
              <img src="<?php echo esc_url($image[0]); ?>" width="<?php echo esc_attr($image[1]);?>" height="<?php echo esc_attr($image[2]);?>" alt="<?php the_title(); ?>" class="iconhover" style="display:block;">
            </a> 
          </div>
          <?php $image = null; $thumbnailURL = null; 
        }  ?>
        
        <div class="postcontent">
          <header>
            <a href="<?php the_permalink() ?>">
              <h5 class="entry-title"><?php the_title(); ?></h5>
            </a>
          </header>
        <div class="entry-content">
            <span class="kt_search_post_type color_gray">
              <?php echo get_post_type( $post->ID ); ?>
            </span>
        </div>
      </div><!-- search content -->
</div> <!-- Search Item -->