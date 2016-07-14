                <article id="post-<?php the_ID(); ?>" <?php post_class('kad_blog_item kad-animation'); ?> data-animation="fade-in" data-delay="0" itemscope="" itemtype="http://schema.org/BlogPosting">
                      <div class="row">
                         <?php global $post, $virtue_premium; $postsummery = get_post_meta( $post->ID, '_kad_post_summery', true );
                          $height = get_post_meta( $post->ID, '_kad_posthead_height', true ); if (!empty($height)) $slideheight = $height; else $slideheight = 400; 
                          $swidth = get_post_meta( $post->ID, '_kad_posthead_width', true ); if (!empty($swidth)) $slidewidth = $swidth; else $slidewidth = 846; 
                          if(empty($postsummery) || $postsummery == 'default') {
                            if(!empty($virtue_premium['post_summery_default'])) {
                            $postsummery = $virtue_premium['post_summery_default'];
                            } else {
                              $postsummery = 'img_portrait';
                            }
                          }
                        if($postsummery == 'img_landscape') { 
                            $textsize = 'col-md-12'; 
                            if (has_post_thumbnail( $post->ID ) ) {
                              $image_url = wp_get_attachment_image_src( 
                              get_post_thumbnail_id( $post->ID ), 'full' ); 
                              $thumbnailURL = $image_url[0];
                              $image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true);
                              if(empty($image)) { $image = $thumbnailURL; }
                              ?>
                              <div class="col-md-12 post-land-image-container">
                                  <div class="imghoverclass img-margin-center">
                                    <a href="<?php the_permalink()  ?>" title="<?php the_title(); ?>">
                                      <img src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>" class="iconhover" width="<?php echo esc_attr($slidewidth);?>" height="<?php echo esc_attr($slideheight);?>" style="display:block;">
                                    </a> 
                                  </div>
                              </div>
                              <?php $image = null; $thumbnailURL = null; 
                            } else {
                                  $thumbnailURL = virtue_post_default_placeholder();
                                  $image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true);
                                  if(empty($image)) { $image = $thumbnailURL; } ?>
                                  <div class="col-md-12 post-land-image-container">
                                  <div class="imghoverclass img-margin-center">
                                    <a href="<?php the_permalink()  ?>" title="<?php the_title(); ?>">
                                            <img src="<?php echo esc_attr($image); ?>" alt="<?php the_title(); ?>" class="iconhover" style="display:block;">
                                        </a> 
                                     </div>
                                 </div>
                                <?php $image = null; $thumbnailURL = null; 
                                }  ?>

                      <?php } elseif($postsummery == 'img_portrait') { 
                            $textsize = 'col-md-7'; 
                            if (has_post_thumbnail( $post->ID ) ) {
                            $image_url = wp_get_attachment_image_src( 
                            get_post_thumbnail_id( $post->ID ), 'full' ); 
                            $thumbnailURL = $image_url[0]; 
                            $image = aq_resize($thumbnailURL, 365, 365, true);
                            if(empty($image)) { $image = $thumbnailURL; } ?>
                            <div class="col-md-5 post-image-container">
                                <div class="imghoverclass img-margin-center">
                                    <a href="<?php the_permalink()  ?>" title="<?php the_title(); ?>">
                                        <img src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>" class="iconhover" width="365" height="365" style="display:block;">
                                    </a> 
                                 </div>
                             </div>
                            <?php $image = null; $thumbnailURL = null; 
                          }  else  {
                             $thumbnailURL = virtue_post_default_placeholder();
                                  $image = aq_resize($thumbnailURL, 365, 365, true);
                                  if(empty($image)) { $image = $thumbnailURL; } ?>
                                  <div class="col-md-5 post-image-container">
                                    <div class="imghoverclass img-margin-center">
                                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                            <img src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>" class="iconhover" style="display:block;">
                                        </a> 
                                     </div>
                                 </div>
                                <?php $image = null; $thumbnailURL = null; 
                                } ?>

                      <?php } elseif($postsummery == 'slider_landscape') {
                            $textsize = 'col-md-12'; ?>
                            <div class="col-md-12 post-land-image-container">
                                <div class="flexslider kt-flexslider loading" style="max-width:<?php echo esc_attr($slidewidth);?>px;" data-flex-speed="7000" data-flex-initdelay="0" data-flex-anim-speed="400" data-flex-animation="fade" data-flex-auto="true">
                                    <ul class="slides">
                                      <?php global $post;
                                        $image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                                            if(!empty($image_gallery)) {
                                              $attachments = array_filter( explode( ',', $image_gallery ) );
                                                if ($attachments) {
                                                foreach ($attachments as $attachment) {
                                                  $attachment_url = wp_get_attachment_url($attachment , 'full');
                                                  $image = aq_resize($attachment_url, $slidewidth, $slideheight, true);
                                                    if(empty($image)) {$image = $attachment_url;} ?>
                                                    <li>
                                                      <a href="<?php the_permalink() ?>" alt="<?php the_title(); ?>">
                                                        <img src="<?php echo esc_url($image); ?>" width="<?php echo esc_attr($slidewidth);?>" height="<?php echo esc_attr($slideheight);?>" class="" />
                                                      </a>
                                                    </li>
                                                <?php 
                                                }
                                              }
                                            } else {
                                              $attach_args = array('order'=> 'ASC','post_type'=> 'attachment','post_parent'=> $post->ID,'post_mime_type' => 'image','post_status'=> null,'orderby'=> 'menu_order','numberposts'=> -1);
                                              $attachments = get_posts($attach_args);
                                                if ($attachments) {
                                                  foreach ($attachments as $attachment) {
                                                    $attachment_url = wp_get_attachment_url($attachment->ID , 'full');
                                                    $image = aq_resize($attachment_url, $slidewidth, $slideheight, true);
                                                      if(empty($image)) {$image = $attachment_url;} ?>
                                                    <li>
                                                      <a href="<?php the_permalink() ?>" alt="<?php the_title(); ?>">
                                                        <img src="<?php echo esc_url($image); ?>" width="<?php echo esc_attr($slidewidth);?>" height="<?php echo esc_attr($slideheight);?>" class="" />
                                                      </a>
                                                    </li>
                                                <?php 
                                                  }
                                                } 
                                            } ?>                                   
                                    </ul>
                                </div> <!--Flex Slides-->
                            </div>

                    <?php } elseif($postsummery == 'slider_portrait') { ?>
                             <?php $textsize = 'col-md-7'; ?>
                              <div class="col-md-5 post-image-container">
                                  <div class="flexslider kt-flexslider loading" data-flex-speed="7000" data-flex-initdelay="0" data-flex-anim-speed="400" data-flex-animation="fade" data-flex-auto="true">
                                      <ul class="slides">
                                         <?php global $post;
                                        $image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                                            if(!empty($image_gallery)) {
                                              $attachments = array_filter( explode( ',', $image_gallery ) );
                                                if ($attachments) {
                                                foreach ($attachments as $attachment) {
                                                  $attachment_url = wp_get_attachment_url($attachment , 'full');
                                                  $image = aq_resize($attachment_url, 365, 365, true);
                                                    if(empty($image)) {$image = $attachment_url;} ?>
                                                    <li>
                                                      <a href="<?php the_permalink() ?>" alt="<?php the_title(); ?>">
                                                        <img src="<?php echo $image ?>" width="365" height="365" class="" />
                                                      </a>
                                                    </li>
                                                <?php 
                                                }
                                              }
                                            } else {
                                              $attach_args = array('order'=> 'ASC','post_type'=> 'attachment','post_parent'=> $post->ID,'post_mime_type' => 'image','post_status'=> null,'orderby'=> 'menu_order','numberposts'=> -1);
                                              $attachments = get_posts($attach_args);
                                                if ($attachments) {
                                                  foreach ($attachments as $attachment) {
                                                    $attachment_url = wp_get_attachment_url($attachment->ID , 'full');
                                                    $image = aq_resize($attachment_url, 365, 365, true);
                                                      if(empty($image)) {$image = $attachment_url;} ?>
                                                    <li>
                                                      <a href="<?php the_permalink() ?>" alt="<?php the_title(); ?>">
                                                        <img src="<?php echo esc_url($image); ?>" width="365" height="365" class="" />
                                                      </a>
                                                    </li>
                                                <?php 
                                                  }
                                                } 
                                            } ?>           
                                      </ul>
                                </div> <!--Flex Slides-->
                            </div>
                    <?php } elseif($postsummery == 'video') {
                           $textsize = 'col-md-12'; ?>
                            <div class="col-md-12 post-land-image-container">
                                <div class="videofit">
                                    <?php global $post; $video = get_post_meta( $post->ID, '_kad_post_video', true ); echo do_shortcode($video); ?>
                                </div>
                            </div>

                    <?php } else { 
                            $textsize = 'col-md-12'; 
                          }?>

                      <div class="<?php echo esc_attr($textsize);?> post-text-container postcontent">
                          <?php get_template_part('templates/entry', 'meta-date'); ?>
                          <header>
                              <a href="<?php the_permalink() ?>"><h3 class="entry-title" itemprop="name headline"><?php the_title(); ?></h3></a>
                                <?php get_template_part('templates/entry', 'meta-subhead'); ?>
                          </header>
                          <div class="entry-content" itemprop="articleBody">
                              <?php the_excerpt(); ?>
                          </div>
                          <footer>
                              <?php $tags = get_the_tags(); if ($tags) { ?> <span class="posttags color_gray"><i class="icon-tag"></i> <?php the_tags('', ', ', ''); ?> </span><?php } ?>
                          </footer>
                        </div><!-- Text size -->
                  </div><!-- row-->
              </article> <!-- Article -->