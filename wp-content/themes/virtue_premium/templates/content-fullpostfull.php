 <?php global $post, $virtue_premium; $headcontent = get_post_meta( $post->ID, '_kad_blog_head', true );
 if(empty($headcontent) || $headcontent == 'default') {
        if(!empty($virtue_premium['post_head_default'])) {
            $headcontent = $virtue_premium['post_head_default'];
        } else {
            $headcontent = 'none';
        }
    }
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
      $slidewidth = 1140;
    }
     if ($headcontent == 'carousel') { ?>
         <section class="postfeat carousel_outerrim loading">
            <div id="post-carousel-gallery<?php echo $post->ID;?>" class="fredcarousel post-carousel-gallery-class fadein-carousel" style="overflow:hidden; height: <?php echo esc_attr($slideheight);?>px">
                <div class="gallery-carousel kad-light-wp-gallery initimagecarousel" data-carousel-container="#post-carousel-gallery<?php echo $post->ID;?>" data-carousel-transition="300" data-carousel-auto="true" data-carousel-speed="7000" data-carousel-id="postimgcarousel<?php echo $post->ID;?>">
                  <?php global $post;
                      $image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                          if(!empty($image_gallery)) {
                            $attachments = array_filter( explode( ',', $image_gallery ) );
                              if ($attachments) {
                                foreach ($attachments as $attachment) {
                                $attachment_url = wp_get_attachment_url($attachment , 'full');
                                $image = aq_resize($attachment_url, null, $slideheight, false, false);
                                  if(empty($image)) {$image = $attachment_url;}
                                   echo '<div class="carousel_gallery_item" style="float:left; margin: 0 5px; width:'.$image[1].'px; height:'.$image[2].'px;"><a href="'.$attachment_url.'" rel="lightbox"><img src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'" alt="'.esc_attr(get_post_field('post_excerpt', $attachment)).'"/></a></div>';
                                }
                              }
                          } else {
                            $attach_args = array('order'=> 'ASC','post_type'=> 'attachment','post_parent'=> $post->ID,'post_mime_type' => 'image','post_status'=> null,'orderby'=> 'menu_order','numberposts'=> -1);
                            $attachments = get_posts($attach_args);
                              if ($attachments) {
                                foreach ($attachments as $attachment) {
                                  $attachment_url = wp_get_attachment_url($attachment->ID , 'full');
                                  $image = aq_resize($attachment_url, null, $slideheight, false, false);
                                    if(empty($image)) {$image = $attachment_url;}
                                  echo '<div class="carousel_gallery_item" style="float:left; margin: 0 5px; width:'.$image[1].'px; height:'.$image[2].'px;"><a href="'.$attachment_url.'" rel="lightbox"><img src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'" alt="'.esc_attr(get_post_field('post_excerpt', $attachment)).'"/></a></div>';
                                }
                              } 
                          } ?>                           
            </div> <!--post gallery carousel-->
            <div class="clearfix"></div>
              <a id="prevport-postimgcarousel<?php echo $post->ID;?>" class="prev_carousel icon-arrow-left" href="#"></a>
              <a id="nextport-postimgcarousel<?php echo $post->ID;?>" class="next_carousel icon-arrow-right" href="#"></a>
          </div> <!--fredcarousel-->
        </section>
        <?php } else if ($headcontent == 'shortcode') { ?>
      <div class="sliderclass">
        <?php $shortcodeslider = get_post_meta( $post->ID, '_kad_post_shortcode', true );
        if(!empty($shortcodeslider)) { echo do_shortcode( $shortcodeslider ); } ?>
        </div><!--sliderclass-->
      <?php } ?>
          <article <?php post_class("kad_blog_item"); ?> itemscope="" itemtype="http://schema.org/BlogPosting">
          <?php if ($headcontent == 'flex') { ?>
              <section class="postfeat">
                <div class="flexslider loading kt-flexslider" style="max-width:<?php echo $slidewidth;?>px;" data-flex-speed="7000" data-flex-anim-speed="400" data-flex-animation="fade" data-flex-auto="true">
                <ul class="slides">
                  <?php global $post;
                      $image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                          if(!empty($image_gallery)) {
                            $attachments = array_filter( explode( ',', $image_gallery ) );
                              if ($attachments) {
                              foreach ($attachments as $attachment) {
                                $attachment_url = wp_get_attachment_url($attachment , 'full');
                                $image = aq_resize($attachment_url, $slidewidth, $slideheight, true);
                                  if(empty($image)) {$image = $attachment_url;}
                                echo '<li><img src="'.$image.'"/></li>';
                              }
                            }
                          } else {
                            $attach_args = array('order'=> 'ASC','post_type'=> 'attachment','post_parent'=> $post->ID,'post_mime_type' => 'image','post_status'=> null,'orderby'=> 'menu_order','numberposts'=> -1);
                            $attachments = get_posts($attach_args);
                              if ($attachments) {
                                foreach ($attachments as $attachment) {
                                  $attachment_url = wp_get_attachment_url($attachment->ID , 'full');
                                  $image = aq_resize($attachment_url, $slidewidth, $slideheight, true);
                                    if(empty($image)) {$image = $attachment_url;}
                                  echo '<li><img src="'.$image.'"/></li>';
                                }
                              } 
                          } ?>        
            </ul>
          </div> <!--Flex Slides-->
        </section>
        <?php } else if ($headcontent == 'carouselslider') { ?>
        <section class="postfeat">
          <div id="imageslider" class="loading">
            <div class="carousel_slider_outer fredcarousel fadein-carousel" style="overflow:hidden; max-width:<?php echo esc_attr($slidewidth);?>px; height: <?php echo esc_attr($slideheight);?>px; margin-left: auto; margin-right:auto;">
                <div class="carousel_slider kad-light-gallery initcarouselslider" data-carousel-container=".carousel_slider_outer" data-carousel-transition="600" data-carousel-height="<?php echo esc_attr($slideheight); ?>" data-carousel-auto="true" data-carousel-speed="9000" data-carousel-id="carouselslider">
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
                                    echo '<a href="'.esc_url($attachment_url).'" data-rel="lightbox" title="'.esc_attr($caption).'">';
                                    echo '<img src="'.esc_url($image[0]).'" width="'.esc_attr($image[1]).'" height="'.esc_attr($image[2]).'" />';
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
        <?php } else if ($headcontent == 'video') { ?>
        <section class="postfeat">
          <div class="videofit">
              <?php global $post; $video = get_post_meta( $post->ID, '_kad_post_video', true ); echo $video; ?>
          </div>
        </section>
        <?php } else if ($headcontent == 'image') {
                  if (has_post_thumbnail( $post->ID ) ) {          
                          $image_id = get_post_thumbnail_id();
                          $img_url = wp_get_attachment_url( $image_id,'full' );
                          $image = aq_resize( $img_url, $slidewidth, $imageheight, true, false);
                          if(empty($image[0])) {$image = array($img_url,$slidewidth,$imageheight);} 
                                if($image) : ?>
                                  <div class="imghoverclass post-single-img" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                    <a href="<?php echo esc_url($img_url); ?>" rel-data="lightbox" class="">
                                      <img src="<?php echo esc_url($image[0]); ?>" width="<?php echo esc_attr($image[1])?>" height="<?php echo esc_attr($image[2])?>" itemprop="contentUrl" alt="<?php the_title(); ?>" />
                                      <meta itemprop="url" content="<?php echo esc_url($image[0]); ?>">
                                      <meta itemprop="width" content="<?php echo esc_attr($image[1])?>px">
                                      <meta itemprop="height" content="<?php echo esc_attr($image[2])?>px">
                                    </a>
                                  </div>
                                <?php endif; 
                  } 
              } 
    
    get_template_part('templates/entry', 'meta-date'); ?>
    <header>
      <a href="<?php the_permalink() ?>"><h2 class="entry-title" itemprop="name headline"><?php the_title(); ?></h2></a>
      <?php get_template_part('templates/entry', 'meta-subhead'); ?>
    </header>
    <div class="entry-content clearfix" itemprop="articleBody">
    <?php global $more; $more = 0; ?>
       <?php  global $virtue_premium; if(!empty($virtue_premium['post_readmore_text'])) {$readmore = $virtue_premium['post_readmore_text'];} else { $readmore =  __('Read More', 'virtue') ;}
      the_content($readmore); ?>
    </div>
    <footer class="single-footer">
      <?php $tags = get_the_tags(); if ($tags) { ?> <span class="posttags color_gray"><i class="icon-tag"></i> <?php the_tags('', ', ', ''); ?> </span><?php } ?>
      
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'virtue'), 'after' => '</p></nav>')); ?>
        <?php
  if ( comments_open() ) :
    echo '<p class="kad_comments_link">';
      comments_popup_link( 
        __( 'Leave a Reply', 'virtue' ), 
        __( '1 Comment', 'virtue' ), 
        __( '% Comments', 'virtue' ),
        'comments-link',
        __( 'Comments are Closed', 'virtue' )
    );
    echo '</p>';
  endif;
  ?>
    </footer>
  </article>

