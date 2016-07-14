<?php 
global $post, $virtue_premium, $kt_feat_width;
    $height = get_post_meta( $post->ID, '_kad_posthead_height', true ); 
    $swidth = get_post_meta( $post->ID, '_kad_posthead_width', true );
    if (!empty($height)) {
      $slideheight = $height;
      $imageheight = $height;
    } else {
      $slideheight = 400;
      $imageheight = apply_filters('kt_single_post_image_height', 400); 
    }
    if (!empty($swidth)) {
      $slidewidth = $swidth;
    } else {
      $slidewidth = $kt_feat_width;
    }
    $kt_headcontent = get_post_meta( $post->ID, '_kad_blog_head', true );
    if(empty($kt_headcontent) || $kt_headcontent == 'default') {
        if(!empty($virtue_premium['post_head_default'])) {
            $kt_headcontent = $virtue_premium['post_head_default'];
        } else {
            $kt_headcontent = 'none';
        }
    }

if ($kt_headcontent == 'flex') { ?>
              <section class="postfeat">
                <div class="flexslider kad-light-wp-gallery kt-flexslider loading" style="max-width:<?php echo esc_attr($slidewidth);?>px;" data-flex-speed="7000" data-flex-anim-speed="400" data-flex-animation="fade" data-flex-auto="true">
                <ul class="slides">
                  <?php
                      $image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                          if(!empty($image_gallery)) {
                            $attachments = array_filter( explode( ',', $image_gallery ) );
                              if ($attachments) {
                              foreach ($attachments as $attachment) {
                                $attachment_url = wp_get_attachment_url($attachment , 'full');
                                $caption = get_post($attachment)->post_excerpt;
                                $image = aq_resize($attachment_url, $slidewidth, $slideheight, true);
                                  if(empty($image)) {$image = $attachment_url;}
                                echo '<li><a href="'.esc_url($attachment_url).'" rel="lightbox"><img src="'.esc_url($image).'" width="'.esc_attr($slidewidth).'" height="'.esc_attr($slideheight).'" alt="'.esc_attr($caption).'" itemprop="image"/></a></li>';
                              }
                            }
                          } ?>                            
                  </ul>
                </div> <!--Flex Slides-->
              </section>
        <?php } else if ($kt_headcontent == 'carouselslider') { ?>
        <section class="postfeat">
          <div id="imageslider" class="loading">
            <div class="carousel_slider_outer fredcarousel fadein-carousel" style="overflow:hidden; max-width:<?php echo esc_attr($slidewidth);?>px; height: <?php echo esc_attr($slideheight);?>px; margin-left: auto; margin-right:auto;">
                <div class="carousel_slider kad-light-wp-gallery initcarouselslider" data-carousel-container=".carousel_slider_outer" data-carousel-transition="600" data-carousel-height="<?php echo esc_attr($slideheight); ?>" data-carousel-auto="true" data-carousel-speed="9000" data-carousel-id="carouselslider">
                    <?php $image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                          if(!empty($image_gallery)) {
                              $attachments = array_filter( explode( ',', $image_gallery ) );
                                if ($attachments) {
                                  foreach ($attachments as $attachment) {
                                        $attachment_url = wp_get_attachment_url($attachment , 'full');
                                        $image = aq_resize($attachment_url, null, $slideheight, false, false);
                                        $caption = get_post($attachment)->post_excerpt;
                                    if(empty($image)) {$image = array($attachment_url,$slidewidth,$slideheight);} 
                                    echo '<div class="carousel_gallery_item" style="float:left; display: table; position: relative; text-align: center; margin: 0; width:auto; height:'.esc_attr($image[2]).'px;">';
                                    echo '<div class="carousel_gallery_item_inner" style="vertical-align: middle; display: table-cell;">';
                                    echo '<a href="'.esc_url($attachment_url).'" data-rel="lightbox">';
                                    echo '<img src="'.esc_url($image[0]).'" width="'.esc_attr($image[1]).'" height="'.esc_attr($image[2]).'" alt="'.esc_attr($caption).'" itemprop="image" />';
                                    echo '</a>'; ?>
                                    </div>
                                  </div>
                          <?php } } }?>
                    </div>
                    <div class="clearfix"></div>
                      <a id="prevport-carouselslider" class="prev_carousel icon-arrow-left" href="#"></a>
                      <a id="nextport-carouselslider" class="next_carousel icon-arrow-right" href="#"></a>
                  </div> 
          </div>   
        </section>
        <?php } else if ($kt_headcontent == 'video') { ?>
            <section class="postfeat">
                <div class="videofit" style="max-width:<?php echo esc_attr($slidewidth);?>px; margin:0 auto;">
                    <?php $video = get_post_meta( $post->ID, '_kad_post_video', true ); echo do_shortcode($video); ?>
                </div>
            </section>
        <?php } else if ($kt_headcontent == 'image') {
                if (has_post_thumbnail( $post->ID ) ) {          
                  $image_id = get_post_thumbnail_id();
                  $img_url = wp_get_attachment_url( $image_id,'full' );
                  $image = aq_resize( $img_url, $slidewidth, $imageheight, true, false);
                  if(empty($image[0])) {$image = array($img_url,$slidewidth,$imageheight);} 
                        if($image) : ?>
                          <div class="imghoverclass post-single-img" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                            <a href="<?php echo esc_url($img_url); ?>" rel-data="lightbox" class="">
                              <img src="<?php echo esc_url($image[0]); ?>" itemprop="contentUrl" alt="<?php the_title(); ?>" />
                              <meta itemprop="url" content="<?php echo esc_url($image[0]); ?>">
                              <meta itemprop="width" content="<?php echo esc_attr($image[1])?>px">
                              <meta itemprop="height" content="<?php echo esc_attr($image[2])?>px">
                            </a>
                          </div>
                        <?php endif; 
                } 
              }