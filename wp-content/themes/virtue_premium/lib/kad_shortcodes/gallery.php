<?php 
/**
 * Clean up gallery_shortcode()
 *
 * Re-create the [gallery] shortcode and use thumbnails styling from Bootstrap
 *
 * @link http://twitter.github.com/bootstrap/components.html#thumbnails
 */
function kadence_gallery($attr) {
  $post = get_post();
  static $instance = 0;
  $instance++;

  if (!empty($attr['ids'])) {
    if (empty($attr['orderby'])) {
      $attr['orderby'] = 'post__in';
    }
    $attr['include'] = $attr['ids'];
  }

  $output = apply_filters('post_gallery', '', $attr);

  if ($output != '') {
    return $output;
  }

  if (isset($attr['orderby'])) {
    $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
    if (!$attr['orderby']) {
      unset($attr['orderby']);
    }
  }

  extract(shortcode_atts(array(
    'order'      => 'ASC',
    'orderby'    => 'menu_order ID',
    'id'         => $post->ID,
    'itemtag'    => '',
    'icontag'    => '',
    'captiontag' => '',
    'masonry'    => '',
    'link'      => 'file',
    'speed'      => '9000',
    'transpeed'  => '700',
    'trantype'  => 'fade',
    'height'     => '400',
    'width'      => '1140',
    'caption'    => '',
    'type'       => '',
    'scroll'     => '',
    'columns'    => 3,
    'gallery_id'  => (rand(10,100)),
    'autoplay'    => 'true',
    'size'       => 'full',
    'lightboxsize' => 'full',
    'imgwidth'    => '',
    'imgheight'   => '',
    'attachment_page' => 'false',
    'use_image_alt' => 'false',
    'isostyle'   => 'masonry',
    'include'    => '',
    'exclude'    => ''
  ), $attr));

  $id = intval($id);

  if ($order === 'RAND') {
    $orderby = 'none';
  }

  if (!empty($include)) {
    $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

    $attachments = array();
    foreach ($_attachments as $key => $val) {
      $attachments[$val->ID] = $_attachments[$key];
    }
  } elseif (!empty($exclude)) {
    $attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
  } else {
    $attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
  }

  if (empty($attachments)) {
    return '';
  }
  if (empty($caption) || $caption == 'default') {
    global $virtue_premium;
    if(isset($virtue_premium['gallery_captions']) && $virtue_premium['gallery_captions'] == 1)  {
      $caption = 'true';
    } else {
      $caption = 'false';
    }
  }

  if (is_feed()) {
    $output = "\n";
    foreach ($attachments as $att_id => $attachment) {
      $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
    }
    return $output;
  }
  if (isset($type) && $type == 'carousel') {
    // CAROUSEL
  if(empty($scroll) || $scroll == 1) {$scroll = '1';} else {$scroll = '';}
  if ($columns == '2') {$itemsize = 'tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $imgsize = 560; $md = 2; $sm = 2; $xs = 1; $ss = 1;}
              else if ($columns == '1') {$itemsize = 'tcol-lg-12 tcol-md-12 tcol-sm-12 tcol-xs-12 tcol-ss-12'; $imgsize = 560; $md = 1; $sm = 1; $xs = 1; $ss = 1;} 
              else if ($columns == '3'){ $itemsize = 'tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $imgsize = 400; $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
              else if ($columns == '6'){ $itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $imgsize = 240; $md = 6; $sm = 4; $xs = 3; $ss = 2;}
              else if ($columns == '8' || $columns == '9' || $columns == '7'){ $itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-4'; $imgsize = 240; $md = 6; $sm = 4; $xs = 3; $ss = 3;}
              else if ($columns == '12' || $columns == '11'){ $itemsize = 'tcol-lg-1 tcol-md-1 tcol-sm-2 tcol-xs-2 tcol-ss-3'; $imgsize = 240; $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
              else if ($columns == '5'){ $itemsize = 'tcol-lg-25 tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $imgsize = 240; $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
              else {$itemsize = 'tcol-lg-3 tcol-md-3 tcol-sm-4 tcol-xs-4 tcol-ss-12'; $imgsize = 300; $md = 4; $sm = 3; $xs = 3; $ss = 1;}
              if(!empty($imgheight)) {$imgheightsize = $imgheight;} else {$imgheightsize = $imgsize;}
              if(!empty($imgwidth)) {$imgsize = $imgwidth;} else {$imgsize = $imgsize;}
              if(!empty($lightboxsize)) {$attachmentsize = $lightboxsize;} else {$attachmentsize = 'full';}
ob_start(); ?>
  <div class="carousel_outerrim kad-animation" data-animation="fade-in" data-delay="0">
    <div class="home-margin fredcarousel">
      <div id="carouselcontainer-<?php echo esc_attr($gallery_id); ?>" class="rowtight fadein-carousel">
        <div id="carousel-<?php echo esc_attr($gallery_id); ?>" class="clearfix caroufedselgallery initcaroufedsel kad-light-wp-gallery" data-carousel-container="#carouselcontainer-<?php echo esc_attr($gallery_id); ?>" data-carousel-transition="<?php echo esc_attr($transpeed); ?>" data-carousel-scroll="<?php echo esc_attr($scroll);?>" data-carousel-auto="<?php echo esc_attr($autoplay);?>" data-carousel-speed="<?php echo esc_attr($speed);?>" data-carousel-id="<?php echo esc_attr($gallery_id);?>" data-carousel-md="<?php echo esc_attr($md);?>" data-carousel-sm="<?php echo esc_attr($sm);?>" data-carousel-xs="<?php echo esc_attr($xs);?>" data-carousel-ss="<?php echo esc_attr($ss);?>">
            <?php $gid = 0;
                  foreach ($attachments as $id => $attachment) {

                            $attachment_url = wp_get_attachment_url($id);
                            $image = aq_resize($attachment_url, $imgsize, $imgheightsize, true);
                            if(empty($image)) {$image = $attachment_url;}
                        
                        if($attachmentsize != 'full') {
                            $attachment_url = wp_get_attachment_image_src( $id, $attachmentsize);
                            $attachment_url = $attachment_url[0];
                        }
                      
                        $link = isset($attr['link']) && 'post' == $attr['link'] ? wp_get_attachment_link($id, $size, true, false) : wp_get_attachment_link($id, $size, false, false);
                         if($use_image_alt == 'true') {
                          $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
                        } else {
                          $alt = $attachment->post_excerpt;
                        }

                  echo '<div class="'.esc_attr($itemsize).' gallery_item"><div class="carousel_item grid_item">';
                  echo '<a href="'.$attachment_url.'" data-rel="lightbox" class="lightboxhover">';
                  echo '<img src="'.$image.'" width="'.esc_attr($imgsize).'" height="'.esc_attr($imgheightsize).'" data-grid-id="'.esc_attr($gid).'" alt="'.esc_attr($alt).'" class="kt-gallery-img"/>';
                  if (trim($attachment->post_excerpt) && $caption == true) {
                    echo '<div class="caption kad_caption"><div class="kad_caption_inner">' . wptexturize($attachment->post_excerpt) . '</div></div>';
                  }
                  echo '</a>';
                  echo '</div></div>';
                  $gid ++; 
                }?>
        </div>
      </div>
      <div class="clearfix"></div>
      <a id="prevport-<?php echo esc_attr($gallery_id); ?>" class="prev_carousel icon-arrow-left" href="#"></a>
      <a id="nextport-<?php echo esc_attr($gallery_id); ?>" class="next_carousel icon-arrow-right" href="#"></a>
    </div>
  </div>    
  <?php  $output = ob_get_contents();
    ob_end_clean();

  } elseif (isset($type) && $type == 'imagecarousel') { 
    if(!empty($lightboxsize)) {$attachmentsize = $lightboxsize;} else {$attachmentsize = 'full';}
  ob_start(); ?>
      <section class="carousel_outerrim loading">
        <div id="image-carousel-gallery-<?php echo esc_attr($gallery_id); ?>" class="fredcarousel fadein-carousel" style="overflow:hidden; height: <?php echo esc_attr($height);?>px">
            <div class="gallery-carousel kad-light-wp-gallery initimagecarousel" data-carousel-container="#image-carousel-gallery-<?php echo esc_attr($gallery_id); ?>" data-carousel-transition="<?php echo esc_attr($transpeed); ?>" data-carousel-auto="<?php echo esc_attr($autoplay);?>" data-carousel-speed="<?php echo esc_attr($speed);?>" data-carousel-id="galleryimgcarousel-<?php echo esc_attr($gallery_id); ?>">
              <?php 

                            foreach ($attachments as $id => $attachment) {
                                $attachment_url = wp_get_attachment_url($id);
                                $image = aq_resize($attachment_url, null, $height, false, false);
                                  if(empty($image)) {
                                    $image = array();
                                    $image[0] = $attachment_url;
                                    $image[1] = 400;
                                    $image[2] = $height;
                                  }
                                  if($attachmentsize != 'full') {
                                      $attachment_url = wp_get_attachment_image_src( $id, $attachmentsize);
                                      $attachment_url = $attachment_url[0];
                                  }
                                   if($use_image_alt == 'true') {
                                    $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
                                  } else {
                                    $alt = $attachment->post_excerpt;
                                  }

                                  echo '<div class="carousel_gallery_item" style="float:left; margin: 0 5px; width:'.esc_attr($image[1]).'px; height:'.esc_attr($image[2]).'px;">';
                                  echo '<a href="'.esc_url($attachment_url).'" data-rel="lightbox" class="imgcarousellink">';
                                  echo '<img src="'.esc_url($image[0]).'" width="'.esc_attr($image[1]).'" height="'.esc_attr($image[2]).'" alt="'.esc_attr($alt).'" />';
                                  echo '</a></div>';
                              }
                        ?>         
             </div> <!--post gallery carousel-->
            <div class="clearfix"></div>
              <a id="prevport-galleryimgcarousel-<?php echo esc_attr($gallery_id); ?>" class="prev_carousel icon-arrow-left" href="#"></a>
              <a id="nextport-galleryimgcarousel-<?php echo esc_attr($gallery_id); ?>" class="next_carousel icon-arrow-right" href="#"></a>
          </div> <!--fredcarousel-->
        </section>              
  <?php  $output = ob_get_contents();
    ob_end_clean();
  } elseif (isset($type) && $type == 'slider') {

          ob_start(); ?>
                <div id="flexslider<?php echo esc_attr($gallery_id);?>" class="flexslider loading kt-flexslider" style="max-width:<?php echo esc_attr($width);?>px;" data-flex-speed="<?php echo esc_attr($speed); ?>" data-flex-anim-speed="<?php echo esc_attr($transpeed); ?>" data-flex-animation="<?php echo esc_attr($trantype); ?>" data-flex-auto="<?php echo esc_attr($autoplay); ?>">
                    <ul class="slides kad-light-wp-gallery">
                   <?php  foreach ($attachments as $id => $attachment) {
                          $attachment_url = wp_get_attachment_url($id);
                          $image = aq_resize($attachment_url, $width, $height, true);
                          if(empty($image)) {$image = $attachment_url;}
                          if(!empty($lightboxsize)) {$attachmentsize = $lightboxsize;} else {$attachmentsize = 'full';}
                          if($attachmentsize != 'full') {
                            $attachment_url = wp_get_attachment_image_src( $id, $attachmentsize);
                            $attachment_url = $attachment_url[0];
                          }

                          $link = isset($attr['link']) && 'post' == $attr['link'] ? wp_get_attachment_link($id, $size, true, false) : wp_get_attachment_link($id, $size, false, false);
                           if($use_image_alt == 'true') {
                          $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
                        } else {
                          $alt = $attachment->post_excerpt;
                        }

                            echo '<li><a href="'.esc_attr($attachment_url).'" rel="lightbox[pp_gal]" class="lightboxhover">';
                              echo '<img src="'.esc_url($image).'" width="'.esc_attr($width).'" height="'.esc_attr($height).'" alt="'.esc_attr($alt).'" class=""/>';
                                  if (trim($attachment->post_excerpt) && $caption == true) {
                                      echo '<div class="caption flex-caption"><div><div class="captiontext headerfont"><p>' . wptexturize($attachment->post_excerpt) . '</p></div></div></div>';
                                    }
                              echo '</a>';
                            echo '</li>';
                     } ?>
                      </ul>
                  </div> <!--Flex Slides-->
  <?php  $output = ob_get_contents();
    ob_end_clean();
    } else if(isset($type) && $type == 'mosaic') {
    // Mosaic
  global $virtue_premium; if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == 1) {$animate = 1;} else {$animate = 0;}
  $output .= '<div class="kad-mosaic-gallery-wrapper">';
  $output .= '<div id="kad-wp-gallery'.$gallery_id.'" class="kad-wp-gallery init-mosaic-isotope kad-light-mosaic-gallery clearfix" data-fade-in="'.$animate.'" data-iso-selector=".g_item" data-iso-style="packery" data-iso-filter="false">';
    if ($columns == '3') {
      $itemsize_normal = 'tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12 mosiac_item_normal'; $ximgsize_normal = 400;$yimgsize_normal = 400;
      $itemsize_wide = 'tcol-lg-8 tcol-md-8 tcol-sm-8 tcol-xs-12 tcol-ss-12 mosiac_item_wide'; $ximgsize_wide = 800;$yimgsize_wide = 400; $wide_string = '0,8,16,22,30';
      $itemsize_tall = 'tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12 mosiac_item_tall'; $ximgsize_tall = 400;$yimgsize_tall = 800; $tall_string = '5,12,14,27';
      $itemsize_large = 'tcol-lg-8 tcol-md-8 tcol-sm-8 tcol-xs-12 tcol-ss-12 mosiac_item_large'; $ximgsize_large = 800;$yimgsize_large = 800; $large_string = '3,9,19,24';
    } else {
      $itemsize_normal = 'tcol-lg-3 tcol-md-3 tcol-sm-3 tcol-xs-6 tcol-ss-12 mosiac_item_normal'; $ximgsize_normal = 300;$yimgsize_normal = 300;
      $itemsize_wide = 'tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12 mosiac_item_wide'; $ximgsize_wide = 600;$yimgsize_wide = 300; $wide_string = '0,9,16,21,30';
      $itemsize_tall = 'tcol-lg-3 tcol-md-3 tcol-sm-3 tcol-xs-6 tcol-ss-12 mosiac_item_tall'; $ximgsize_tall = 300;$yimgsize_tall = 600; $tall_string = '4,12,18,25';
      $itemsize_large = 'tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12 mosiac_item_large'; $ximgsize_large = 600;$yimgsize_large = 600; $large_string = '1,10,17,22';
    }
if(!empty($lightboxsize)) {$attachmentsize = $lightboxsize;} else {$attachmentsize = 'full';} 

  $i = 0;
  foreach ($attachments as $id => $attachment) {
    if($i == 31){$i = 0;}
    if(in_array($i, explode(',', $wide_string))){
      $mosaic_xsize = $ximgsize_wide;
      $mosaic_ysize = $yimgsize_wide;
      $mosaic_itemsize = $itemsize_wide;
    } else if(in_array($i, explode(',', $large_string))){
      $mosaic_xsize = $ximgsize_large;
      $mosaic_ysize = $yimgsize_large;
      $mosaic_itemsize = $itemsize_large;
    } elseif(in_array($i, explode(',', $tall_string))){
      $mosaic_xsize = $ximgsize_tall;
      $mosaic_ysize = $yimgsize_tall;
      $mosaic_itemsize = $itemsize_tall;
    } else {
      $mosaic_xsize = $ximgsize_normal;
      $mosaic_ysize = $yimgsize_normal;
      $mosaic_itemsize = $itemsize_normal;
    }
    $attachment_url = wp_get_attachment_url($id);
    $image = aq_resize($attachment_url, $mosaic_xsize, $mosaic_ysize, true, false);
    if(empty($image[0])) {$image = array($attachment_url,$mosaic_xsize,$mosaic_ysize);}
    $img_srcset_output = kt_get_srcset_output( $image[1], $image[2], $attachment_url, $id);
    if($attachmentsize != 'full') {
      $attachment_url = wp_get_attachment_image_src( $id, $attachmentsize);
      $attachment_url = $attachment_url[0];
    }
     $lightbox_data = 'data-rel="lightbox"';
    if($link == 'attachment_page' || $attachment_page == 'true') {
      $attachment_url = get_permalink($id);
      $lightbox_data = '';
    }
    if($use_image_alt == 'true') {
      $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
    } else {
      $alt = $attachment->post_excerpt;
    }

    $output .= '<div class="'.$mosaic_itemsize.' g_item"><div class="grid_item kt_item_fade_in kad_gallery_fade_in gallery_item g_mosiac_item">';
        $output .= '<img src="'.esc_url($image[0]).'" width="'.esc_attr($image[1]).'" height="'.esc_attr($image[2]).'" alt="'.esc_attr($alt).'" '.$img_srcset_output.' class="light-dropshaddow"/>';
        $output .= '<a href="'.$attachment_url.'" '.$lightbox_data.' class="lightboxhover">';
          if (trim($attachment->post_excerpt) && $caption == 'true') {
            $output .= '<div class="caption kad_caption"><div class="kad_caption_inner">' . wptexturize($attachment->post_excerpt) . '</div></div>';
          }
     $output .= '</a>';
    $output .= '</div></div>';
    $i ++;
  }
  $output .= '</div>';
  $output .= '</div>';
  } else {
    // NORMAL
  global $virtue_premium; if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == 1) {$animate = 1;} else {$animate = 0;}
  $output .= '<div id="kad-wp-gallery'.$gallery_id.'" class="kad-wp-gallery kt-gallery-column-'.$columns.' init-isotope kad-light-wp-gallery clearfix rowtight" data-fade-in="'.$animate.'" data-iso-selector=".g_item" data-iso-style="'.$isostyle.'" data-iso-filter="false">';
    if ($columns == '2') {$itemsize = 'tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $imgsize = 560;} 
    else if ($columns == '1') {$itemsize = 'tcol-lg-12 tcol-md-12 tcol-sm-12 tcol-xs-12 tcol-ss-12'; $imgsize = 560;} 
    else if ($columns == '3'){ $itemsize = 'tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $imgsize = 366;} 
    else if ($columns == '6'){ $itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $imgsize = 240;}
    else if ($columns == '8' || $columns == '9' || $columns == '7'){ $itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-4'; $imgsize = 230;}
    else if ($columns == '12' || $columns == '11'){ $itemsize = 'tcol-lg-1 tcol-md-1 tcol-sm-2 tcol-xs-2 tcol-ss-3'; $imgsize = 240;} 
    else if ($columns == '5'){ $itemsize = 'tcol-lg-25 tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $imgsize = 240;} 
    else {$itemsize = 'tcol-lg-3 tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $imgsize = 269;}
  if(!empty($imgheight)) {$imgheightsize = $imgheight;} else {$imgheightsize = $imgsize;}
  if(!empty($imgwidth)) {$imgsize = $imgwidth;} else {$imgsize = $imgsize;}
  if(!empty($lightboxsize)) {$attachmentsize = $lightboxsize;} else {$attachmentsize = 'full';} 

  $i = 0;
  foreach ($attachments as $id => $attachment) {
    $attachment_url = wp_get_attachment_url($id);
    if(!empty($masonry) || $masonry == 'default') {
      if($masonry == 'true'){
        $image = aq_resize($attachment_url, $imgsize, null, false, false);
      } else {
         $image = aq_resize($attachment_url, $imgsize, $imgheightsize, true, false);
      }

    } else {
          if(isset($virtue_premium['virtue_gallery_masonry']) && $virtue_premium['virtue_gallery_masonry'] ==  '1') {
          $image = aq_resize($attachment_url, $imgsize, null, false, false);
        } else {
          $image = aq_resize($attachment_url, $imgsize, $imgheightsize, true, false);
        }
    }
    if(empty($image[0])) {$image = array($attachment_url,$imgsize,$imgheightsize);} 
    // Get srcset
    $img_srcset = kt_get_srcset($image[1],$image[2], $attachment_url, $id);
    
    // Get alt and fall back to title if no alt
    if($attachmentsize != 'full') {
      $attachment_url = wp_get_attachment_image_src( $id, $attachmentsize);
      $attachment_url = $attachment_url[0];
    }
    $lightbox_data = 'data-rel="lightbox"';
    if($link == 'attachment_page' || $attachment_page == 'true') {
      $attachment_url = get_permalink($id);
      $lightbox_data = '';
    }
    
    if($use_image_alt == 'true') {
      $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
    } else {
      $alt = $attachment->post_excerpt;
    }
    $output .= '<div class="'.$itemsize.' g_item"><div class="grid_item kt_item_fade_in kad_gallery_fade_in gallery_item"><a href="'.esc_url($attachment_url).'" '.$lightbox_data.' class="lightboxhover">';
    $output .= '<img src="'.esc_url($image[0]).'" width="'.esc_attr($image[1]).'" height="'.esc_attr($image[2]).'" alt="'.esc_attr($alt).'"';
    if(!empty($img_srcset)) { 
      $output .= ' srcset="'.esc_attr($img_srcset).'" sizes="(max-width:'.esc_attr($image[1]).'px) 100vw, '.esc_attr($image[1]).'px" ';
    }
    $output .= 'class="light-dropshaddow"/>';
      if (trim($attachment->post_excerpt) && $caption == 'true') {
        $output .= '<div class="caption kad_caption"><div class="kad_caption_inner">' . wptexturize($attachment->post_excerpt) . '</div></div>';
      }
    $output .= '</a>';
    $output .= '</div></div>';
  }
  $output .= '</div>';
  }
  
  return $output;
}
add_action('init', 'kt_gallery_setup_init');
function kt_gallery_setup_init() {
  global $virtue_premium;
  if(isset($virtue_premium['virtue_gallery']) && $virtue_premium['virtue_gallery'] == '1')  {
  remove_shortcode('gallery');
  add_shortcode('gallery', 'kadence_gallery');
  }
}