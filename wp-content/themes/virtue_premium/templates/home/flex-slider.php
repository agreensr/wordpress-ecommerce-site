<?php  
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
    global $virtue_premium;

        if(isset($virtue_premium['slider_size'])) {$slideheight = $virtue_premium['slider_size'];} else { $slideheight = 400; }
        if(isset($virtue_premium['slider_size_width'])) {$slidewidth = $virtue_premium['slider_size_width'];} else { $slidewidth = 1140; }
        if(isset($virtue_premium['slider_captions'])) { $captions = $virtue_premium['slider_captions']; } else {$captions = '';}
        if(isset($virtue_premium['home_slider'])) {$slides = $virtue_premium['home_slider']; } else {$slides = '';}
        if(isset($virtue_premium['trans_type'])) {$transtype = $virtue_premium['trans_type'];} else { $transtype = 'slide';}
        if(isset($virtue_premium['slider_transtime'])) {$transtime = $virtue_premium['slider_transtime'];} else {$transtime = '300';}
        if(isset($virtue_premium['slider_autoplay']) && $virtue_premium['slider_autoplay'] == "1" ) {$autoplay ='true';} else {$autoplay = 'false';}
        if(isset($virtue_premium['slider_pausetime'])) {$pausetime = $virtue_premium['slider_pausetime'];} else {$pausetime = '7000';}
                ?>
<div class="sliderclass">
  <div id="imageslider" class="container">
    <div class="flexslider kt-flexslider loading" style="max-width:<?php echo esc_attr($slidewidth);?>px; margin-left: auto; margin-right:auto;" data-flex-speed="<?php echo esc_attr($pausetime);?>" data-flex-initdelay="0" data-flex-anim-speed="<?php echo esc_attr($transtime);?>" data-flex-animation="<?php echo esc_attr($transtype); ?>" data-flex-auto="<?php echo esc_attr($autoplay);?>">
        <ul class="slides">
            <?php foreach ($slides as $slide) :
                    if(!empty($slide['target']) && $slide['target'] == 1) {$target = '_blank';} else {$target = '_self';} 
                    $image = aq_resize($slide['url'], $slidewidth, $slideheight, true);
                    if(empty($image)) {$image = $slide['url'];} 
                        ?>
                      <li> 
                        <?php if($slide['link'] != '') echo '<a href="'.esc_url($slide['link']).'" target="'.esc_attr($target).'">'; ?>
                          <img src="<?php echo esc_url($image); ?>" width="<?php echo esc_attr($slidewidth);?>" height="<?php echo esc_attr($slideheight);?>" alt="<?php echo esc_attr($slide['title']); ?>" />
                              <?php if ($captions == '1') { ?> 
                                <div class="flex-caption">
                                <?php if ($slide['title'] != '') echo '<div class="captiontitle headerfont">'.$slide['title'].'</div>'; ?>
                                <?php if ($slide['description'] != '') echo '<div><div class="captiontext headerfont"><p>'.$slide['description'].'</p></div></div>';?>
                                </div> 
                              <?php } ?>
                        <?php if($slide['link'] != '') echo '</a>'; ?>
                      </li>
                  <?php endforeach; ?>
        </ul>
      </div> <!--Flex Slides-->
  </div><!--Container-->
</div><!--sliderclass-->