<section class="pagefeat carousel_outerrim loading">
  <?php global $post; $height = get_post_meta( $post->ID, '_kad_posthead_height', true ); if (!empty($height)) $slideheight = $height; else $slideheight = 400;  ?>
        <div id="post-carousel-gallery" class="fredcarousel fadein-carousel" style="overflow:hidden; height: <?php echo esc_attr($slideheight);?>px">
            <div class="gallery-carousel initimagecarousel" data-carousel-container="#post-carousel-gallery" data-carousel-transition="300" data-carousel-auto="true" data-carousel-speed="7000" data-carousel-id="pageimgcarousel">
              <?php 
                      $image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
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
                                  echo '<img src="'.esc_url($image[0]).'" width="'.esc_attr($image[1]).'" height="'.esc_attr($image[2]).'" /></div>';
                                }
                              }
                          } else {
                            $attach_args = array('order'=> 'ASC','post_type'=> 'attachment','post_parent'=> $post->ID,'post_mime_type' => 'image','post_status'=> null,'orderby'=> 'menu_order','numberposts'=> -1);
                            $attachments = get_posts($attach_args);
                              if ($attachments) {
                                foreach ($attachments as $attachment) {
                                  $attachment_url = wp_get_attachment_url($attachment->ID , 'full');
                                  $image = aq_resize($attachment_url, null, $slideheight, false, false);
                                    if(empty($image)) {
                                    $image = array();
                                    $image[0] = $attachment_url;
                                    $image[1] = 400;
                                    $image[2] = $slideheight;
                                  }
                                  echo '<div class="carousel_gallery_item" style="float:left; margin: 0 5px; width:'.esc_attr($image[1]).'px; height:'.esc_attr($image[2]).'px;">';
                                  echo '<img src="'.esc_url($image[0]).'" width="'.esc_attr($image[1]).'" height="'.esc_attr($image[2]).'" /></div>';
                                }
                              } 
                          } ?>         
             </div> <!--post gallery carousel-->
            <div class="clearfix"></div>
              <a id="prevport-pageimgcarousel" class="prev_carousel icon-arrow-left" href="#"></a>
              <a id="nextport-pageimgcarousel" class="next_carousel icon-arrow-right" href="#"></a>
          </div> <!--fredcarousel-->
        </section>              