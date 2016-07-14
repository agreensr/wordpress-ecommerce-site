<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
  global $virtue_premium; 

$cc_items = $virtue_premium['home_custom_carousel_items'];
if(!empty($virtue_premium['custom_carousel_title'])) {
  $cctitle = $virtue_premium['custom_carousel_title']; 
} else { 
  $cctitle = __('Featured News', 'virtue'); 
}
if(!empty($virtue_premium['home_custom_speed'])) {
  $hc_speed = $virtue_premium['home_custom_speed'].'000';
} else {
  $hc_speed = '9000';
}
if(isset($virtue_premium['home_custom_carousel_scroll']) && $virtue_premium['home_custom_carousel_scroll'] == 'all' ) {
  $hc_scroll = '';
} else {
  $hc_scroll = '1';
}
if(!empty($virtue_premium['home_custom_carousel_column'])) {
  $custom_column = $virtue_premium['home_custom_carousel_column'];
} else {
  $custom_column = 4;
} 
$cc = array();
if ($custom_column == '2') {
  $itemsize = 'tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; 
  $slidewidth = 559;  
  $cc['md'] = 2; 
  $cc['sm'] = 2; 
  $cc['xs'] = 1;
  $cc['ss'] = 1;
} else if ($custom_column == '3'){
  $itemsize = 'tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; 
  $slidewidth = 366; 
  $cc['md'] = 3; 
  $cc['sm'] = 3; 
  $cc['xs'] = 2;
  $cc['ss'] = 1; 
} else if ($custom_column == '6'){
  $itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; 
  $slidewidth = 240; 
  $cc['md'] = 6; 
  $cc['sm'] = 4; 
  $cc['xs'] = 3;
  $cc['ss'] = 2;
} else if ($custom_column == '5'){
  $itemsize = 'tcol-lg-25 tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; 
  $slidewidth = 240; 
  $cc['md'] = 5; 
  $cc['sm'] = 4; 
  $cc['xs'] = 3;
  $cc['ss'] = 2;
} else {
  $itemsize = 'tcol-lg-3 tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12';
  $slidewidth = 267; 
  $cc['md'] = 4; 
  $cc['sm'] = 3; 
  $cc['xs'] = 2;
  $cc['ss'] = 1; 
}
$slideheight = $slidewidth;
$cc = apply_filters('kt_home_custom_carousel_columns', $cc);

?>
<div class="home-custom-carousel-wrap home-margin carousel_outerrim home-padding kad-animation" data-animation="fade-in" data-delay="0">
  <div class="clearfix">
    <h3 class="hometitle">
      <?php echo $cctitle; ?>
    </h3>
  </div>
  
  <div class="fredcarousel">
    <div id="carouselcontainer-hcustom_carousel" class="rowtight">
      <div id="home-custom-carousel" class="clearfix caroufedselclass initcaroufedsel" data-carousel-container="#carouselcontainer-hcustom_carousel" data-carousel-transition="700" data-carousel-scroll="<?php echo esc_attr($hc_scroll);?>" data-carousel-auto="true" data-carousel-speed="<?php echo esc_attr($hc_speed);?>" data-carousel-id="hcustom_carousel" data-carousel-md="<?php echo esc_attr($cc['md']);?>" data-carousel-sm="<?php echo esc_attr($cc['sm']);?>" data-carousel-xs="<?php echo esc_attr($cc['xs']);?>" data-carousel-ss="<?php echo esc_attr($cc['ss']);?>">
        <?php
        if(!empty($cc_items)) {

          foreach ($cc_items as $c_item) :

            if(!empty($c_item['target']) && $c_item['target'] == 1) {
              $target = '_blank';
            } else {
              $target = '_self';
            }
            if(isset($virtue_premium['home_custom_carousel_imageratio']) && $virtue_premium['home_custom_carousel_imageratio'] == '1' ) {
                  $image = aq_resize($c_item['url'], $slidewidth, null, false, false);
            } else {
                    $image = aq_resize($c_item['url'], $slidewidth, $slideheight, true, false);
            } 
            if(empty($image[0])) {$image[0] = $c_item['url']; $image[1] = null; $image[2] = null;}
            ?>
            <div class="<?php echo esc_attr($itemsize); ?> kad_customcarousel_item">
              <div class="grid_item product product_item custom_carousel_item all postclass">
                <a href="<?php if(!empty($c_item['link'])) echo esc_url($c_item['link']); ?>" class="custom_carousel_item_link" target="<?php echo esc_attr($target); ?>">
                    <img src="<?php echo esc_url($image[0]); ?>" width="<?php echo esc_attr($image[1]); ?>" height="<?php echo esc_attr($image[2]); ?>" alt="<?php if(!empty($c_item['title'])) echo esc_attr($c_item['title']);?>" />
                </a>
                
                <div class="custom_carousel_details">
                    <a href="<?php if(!empty($c_item['link'])) echo esc_url($c_item['link']); ?>">
                             <?php if ($c_item['title']) echo '<h5>'.esc_html($c_item['title']).'</h5>'; ?>
                    </a>
                    <div class="ccarousel_excerpt"><?php if(!empty($c_item['description'])) echo $c_item['description'];?></div>
                </div>
              </div>
            </div>
            <?php endforeach; 
        } ?>
      </div>
    <div class="clearfix"></div>
      <a id="prevport-hcustom_carousel" class="prev_carousel icon-arrow-left" href="#"></a>
      <a id="nextport-hcustom_carousel" class="next_carousel icon-arrow-right" href="#"></a>
    </div>
  </div>
</div>