<?php get_template_part('templates/head'); ?>
<?php global $virtue_premium; 
  if(isset($virtue_premium["smooth_scrolling"]) && $virtue_premium["smooth_scrolling"] == '1') {$scrolling = '1';} else {$scrolling = '0';}
  if(isset($virtue_premium["smooth_scrolling_hide"]) && $virtue_premium["smooth_scrolling_hide"] == '1') {$scrolling_hide = '1';} else {$scrolling_hide = '0';} 
  if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == '1') {$animate = '1';} else {$animate = '0';}
  if(isset($virtue_premium['sticky_header']) && $virtue_premium['sticky_header'] == '1') {$sticky = '1';} else {$sticky = '0';}
  if(isset($virtue_premium['product_tabs_scroll']) && $virtue_premium['product_tabs_scroll'] == '1') {$pscroll = '1';} else {$pscroll = '0';}
  if(isset($virtue_premium['header_style'])) {$header_style = $virtue_premium['header_style'];} else {$header_style = 'standard';}
  ?>
<body <?php body_class(); ?> data-smooth-scrolling="<?php echo $scrolling;?>" data-smooth-scrolling-hide="<?php echo $scrolling_hide;?>" data-product-tab-scroll="<?php echo $pscroll; ?>" data-animate="<?php echo $animate;?>" data-sticky="<?php echo $sticky;?>">
<div id="wrapper" class="container">
  <!--[if lt IE 8]><div class="alert">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</div><![endif]-->

  <div class="wrap contentclass" style="padding:0px;" role="document">
<?php include kadence_template_path(); ?>      


  </div><!-- /.wrap -->
<?php do_action('get_footer'); ?>
<?php wp_footer(); ?>
</div><!--Wrapper-->
</body>
</html>
