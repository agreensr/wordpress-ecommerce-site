<?php  
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
  global $virtue_premium; 
          if(isset($virtue_premium['slider_size'])) {$slideheight = $virtue_premium['slider_size'];} else { $slideheight = 400; }
          if(isset($virtue_premium['slider_size_width'])) {$slidewidth = $virtue_premium['slider_size_width'];} else { $slidewidth = 1140; }
          if(isset($virtue_premium['trans_type'])) {$transtype = $virtue_premium['trans_type'];} else { $transtype = 'slide';}
          if(isset($virtue_premium['slider_transtime'])) {$transtime = $virtue_premium['slider_transtime'];} else {$transtime = '300';}
          if(isset($virtue_premium['slider_autoplay']) && $virtue_premium['slider_autoplay'] == "1" ) {$autoplay ='true';} else {$autoplay = 'false';}
          if(isset($virtue_premium['slider_pausetime'])) {$pausetime = $virtue_premium['slider_pausetime'];} else {$pausetime = '7000';}
        ?>
  <div class="sliderclass">
    <div id="imageslider" class="container">
      <div class="flexslider kt-flexslider loading" style="max-width:<?php echo esc_attr($slidewidth);?>px; margin-left: auto; margin-right:auto;" data-flex-speed="<?php echo esc_attr($pausetime);?>" data-flex-initdelay="0" data-flex-anim-speed="<?php echo esc_attr($transtime);?>" data-flex-animation="<?php echo esc_attr($transtype); ?>" data-flex-auto="<?php echo esc_attr($autoplay);?>">
          <ul class="slides">
            <?php $temp = $wp_query; 
            $wp_query = null; 
            $wp_query = new WP_Query();
            $wp_query->query(array('posts_per_page' => 4));

              if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
                  if (has_post_thumbnail( $post->ID ) ) {
                              $image_url = wp_get_attachment_image_src( 
                              get_post_thumbnail_id( $post->ID ), 'full' ); 
                              $thumbnailURL = $image_url[0]; 
                              $image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true);
                              if(empty($image)) { $image = $thumbnailURL; } 
                    ?>
                      <li> 
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php echo esc_attr($image); ?>" width="<?php echo esc_attr($slidewidth);?>" height="<?php echo esc_attr($slideheight);?>" alt="<?php the_title(); ?>" />
                            <div class="flex-caption">
                              <div class="captiontitle headerfont">
                                <?php the_title(); ?>
                              </div>
                            </div> 
                        </a>
                      </li>
                  <?php } endwhile; else: ?>
            <li class="error-not-found"><?php _e('Sorry, no blog entries found.', 'virtue'); ?></li>
          <?php endif; ?>
        <?php $wp_query = null; $wp_query = $temp;  // Reset ?>
        <?php wp_reset_query(); ?>
        </ul>
      </div> <!--Flex Slides-->
  </div><!--Container-->
</div><!--sliderclass-->