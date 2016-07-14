<?php 
global $post, $virtue_premium;
    $height = get_post_meta( $post->ID, '_kad_posthead_height', true ); 
    if (!empty($height)) {
      $slideheight = $height;
    } else {
      $slideheight = 400;
    }
    $kt_headcontent = get_post_meta( $post->ID, '_kad_blog_head', true );
    if(empty($kt_headcontent) || $kt_headcontent == 'default') {
        if(!empty($virtue_premium['post_head_default'])) {
            $kt_headcontent = $virtue_premium['post_head_default'];
        } else {
            $kt_headcontent = 'none';
        }
    }
if ($kt_headcontent == 'carousel') { ?>
        <section class="postfeat carousel_outerrim loading">
            <div id="post-carousel-gallery" class="fredcarousel fadein-carousel" style="overflow:hidden; height: <?php echo esc_attr($slideheight);?>px">
                <div class="gallery-carousel kad-light-wp-gallery initimagecarousel" data-carousel-container="#post-carousel-gallery" data-carousel-transition="300" data-carousel-auto="true" data-carousel-speed="7000" data-carousel-id="postimgcarousel">
                  <?php $image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                          if(!empty($image_gallery)) {
                            $attachments = array_filter( explode( ',', $image_gallery ) );
                              if ($attachments) {
                                foreach ($attachments as $attachment) {
                                      $attachment_url = wp_get_attachment_url($attachment , 'full');
                                $image = aq_resize($attachment_url, null, $slideheight, false, false);
                                   if(empty($image)) {
                                    $image = array();
                                    $image[0] = $attachment_url;
                                    $image[1] = 400;
                                    $image[2] = $slideheight;
                                  }
                                  echo '<div class="carousel_gallery_item" style="float:left; margin: 0 5px; width:'.esc_attr($image[1]).'px; height:'.esc_attr($image[2]).'px;">';
                                  echo '<a href="'.esc_url($attachment_url).'" data-rel="lightbox">';
                                  echo '<img src="'.esc_url($image[0]).'" width="'.esc_attr($image[1]).'" height="'.esc_attr($image[2]).'" itemprop="image" alt="'.esc_attr(get_post_field('post_excerpt', $attachment)).'"/>';
                                  echo '</a></div>';
                                }
                              }
                          } ?>                           
              </div> <!--post gallery carousel-->
            <div class="clearfix"></div>
              <a id="prevport-postimgcarousel" class="prev_carousel icon-arrow-left" href="#"></a>
              <a id="nextport-postimgcarousel" class="next_carousel icon-arrow-right" href="#"></a>
          </div> <!--fredcarousel-->
        </section>
<?php } else if ($kt_headcontent == 'shortcode') { ?>
      <div class="sliderclass">
        <?php 
        $shortcodeslider = get_post_meta( $post->ID, '_kad_post_shortcode', true );
        if(!empty($shortcodeslider)) { 
            echo do_shortcode( $shortcodeslider ); 
        } ?>
        </div><!--sliderclass-->
<?php } 
