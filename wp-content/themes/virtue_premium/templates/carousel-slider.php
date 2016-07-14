<section class="pagefeat carousel_outerrim">
  <?php global $post; $height = get_post_meta( $post->ID, '_kad_posthead_height', true ); if (!empty($height)) $slideheight = $height; else $slideheight = 400; 
  $swidth = get_post_meta( $post->ID, '_kad_posthead_width', true ); if (!empty($swidth)) $slidewidth = $swidth; else $slidewidth = 1140; ?>
    <div id="imageslider" class="loading">
    <div class="carousel_slider_outer fredcarousel fadein-carousel" style="overflow:hidden; max-width:<?php echo $slidewidth;?>px; height: <?php echo $slideheight;?>px; margin-left: auto; margin-right:auto;">
        <div class="carousel_slider">
            <?php $image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
                  if(!empty($image_gallery)) {
                      $attachments = array_filter( explode( ',', $image_gallery ) );
                        if ($attachments) {
                          foreach ($attachments as $attachment) {
                                $attachment_url = wp_get_attachment_url($attachment , 'full');
                                $image = aq_resize($attachment_url, null, $slideheight, false, false);
                            if(empty($image)) {$image = array($attachment_url,$slidewidth,$slideheight);} 
                            echo '<div class="carousel_gallery_item" style="float:left; display: table; position: relative; text-align: center; margin: 0; width:auto; height:'.$image[2].'px;">';
                            echo '<div class="carousel_gallery_item_inner" style="vertical-align: middle; display: table-cell;">';
                            echo '<img src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'" />';
                              ?>
                            </div>
                          </div>
                  <?php } } }?>
            </div>
            <div class="clearfix"></div>
              <a id="prevport_carouselslider" class="prev_carousel icon-arrow-left" href="#"></a>
              <a id="nextport_carouselslider" class="next_carousel icon-arrow-right" href="#"></a>
          </div> 
  </div>
</section>

 
                <script type="text/javascript">
                jQuery( window ).load(function () {
                    var $wcontainer = jQuery('.carousel_slider_outer');
                    var $container = jQuery('.carousel_slider_outer .carousel_slider');
                      var align = 'center';
                      var carheight = <?php echo $slideheight; ?>;
                          setWidths();
                          $container.carouFredSel({
                              width: '100%',
                              height: carheight,
                              align: align,
                              auto: {play: true, timeoutDuration: 9000},
                              scroll: {
                                items : 1,
                                easing: 'quadratic'
                              },
                              items: {
                                visible: 1,
                                width: 'variable'
                              },
                              prev: '.carousel_slider_outer .prev_carousel',
                              next: '.carousel_slider_outer .next_carousel',
                              swipe: {
                                onMouse: false,
                                onTouch: true
                              },
                              onCreate: function() {
                                jQuery('.carousel_slider').css('positon','static');
                              }
                            });
                            jQuery(window).on("debouncedresize", function( event ) {
                            setWidths();
                              $container.trigger("updateSizes");
                            });
                          $wcontainer.animate({'opacity' : 1});
                          $wcontainer.css({ height: 'auto' });
                          $wcontainer.parent().removeClass('loading');
                          // set all the widths to the elements
                          function setWidths() {
                            var unitWidth = $container.width();
                            $container.children().css({ width: unitWidth });
                            if(jQuery(window).width() <= 768) {
                            carheight = null;
                            $container.children().css({ height: 'auto' });
                          }
                        }

                });
                
              </script>                     
            