<?php
/**
 * Enqueue scripts and stylesheets
 */

function kadence_scripts() {
  wp_enqueue_style('kadence_app', get_template_directory_uri() . '/assets/css/virtue.css', false, '365');
  global $virtue_premium; if(isset($virtue_premium['skin_stylesheet']) && !empty($virtue_premium['skin_stylesheet'])) {$skin = $virtue_premium['skin_stylesheet'];} else { $skin = 'default.css';}
   $skin_stylesheet_path = apply_filters('kt_skin_style_path_output', get_template_directory_uri() . '/assets/css/skins/'); 
  wp_enqueue_style('virtue_skin', $skin_stylesheet_path.$skin.'', false, null);

if (is_child_theme()) {
   wp_enqueue_style('kadence_child', get_stylesheet_uri(), false, null);
  } 
  
  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_register_script('modernizr', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.7.0.min.js', false, null, false);
  wp_register_script('kadence_plugins', get_template_directory_uri() . '/assets/js/plugins.js', false, 365, true);
  wp_register_script('kadence_main', get_template_directory_uri() . '/assets/js/main.js', false, 365, true);
  wp_enqueue_script('jquery');
  wp_enqueue_script('modernizr');
  wp_enqueue_script('kadence_plugins');
  if(isset($virtue_premium["smooth_scrolling"]) && $virtue_premium["smooth_scrolling"] == '1') { 
     wp_register_script('virtue_smoothscroll', get_template_directory_uri() . '/assets/js/min/nicescroll-min.js', false, null, false);
     wp_enqueue_script('virtue_smoothscroll');
  } else if(isset($virtue_premium["smooth_scrolling"]) && $virtue_premium["smooth_scrolling"] == '2') { 
    wp_register_script('virtue_smoothscroll', get_template_directory_uri() . '/assets/js/min/smoothscroll-min.js', false, null, true);
    wp_enqueue_script('virtue_smoothscroll');
  }
  wp_enqueue_script('kadence_main');

  if((isset($virtue_premium['infinitescroll']) && $virtue_premium['infinitescroll'] == 1) || (isset($virtue_premium['blog_infinitescroll']) && $virtue_premium['blog_infinitescroll'] == 1) || (isset($virtue_premium['blog_cat_infinitescroll']) && $virtue_premium['blog_cat_infinitescroll'] == 1)) {
    wp_register_script('infinite_scroll', get_template_directory_uri() . '/assets/js/jquery.infinitescroll.js', false, null, true);
    wp_enqueue_script('infinite_scroll');
  }

  if(class_exists('woocommerce')) {
    if(isset($virtue_premium['product_radio']) && $virtue_premium['product_radio'] == 1) {
      wp_deregister_script('wc-add-to-cart-variation');
      wp_register_script( 'wc-add-to-cart-variation', get_template_directory_uri() . '/assets/js/min/add-to-cart-variation-radio-min.js' , array( 'jquery' ), false, '360', true );
          wp_localize_script( 'wc-add-to-cart-variation', 'wc_add_to_cart_variation_params', apply_filters( 'wc_add_to_cart_variation_params', array(
        'i18n_no_matching_variations_text' => esc_attr__( 'Sorry, no products matched your selection. Please choose a different combination.', 'virtue' ),
        'i18n_unavailable_text'            => esc_attr__( 'Sorry, this product is unavailable. Please choose a different combination.', 'virtue' ),
      ) ) );
          wp_enqueue_script( 'wc-add-to-cart-variation');
    } else {
     wp_register_script( 'kt-wc-add-to-cart-variation', get_template_directory_uri() . '/assets/js/min/kt-add-to-cart-variation-min.js' , array( 'jquery' ), false, '360', true );
     wp_enqueue_script( 'kt-wc-add-to-cart-variation');
    }
    if(isset($virtue_premium['product_quantity_input']) && $virtue_premium['product_quantity_input'] == 1) {
      function kt_get_wc_version() {return defined( 'WC_VERSION' ) && WC_VERSION ? WC_VERSION : null;}
      function kt_is_wc_version_gte_2_3() {return kt_get_wc_version() && version_compare(kt_get_wc_version(), '2.3', '>=' );}
      if (kt_is_wc_version_gte_2_3() ) {
        wp_register_script( 'wcqi-js', get_template_directory_uri() . '/assets/js/min/wc-quantity-increment.min.js' , array( 'jquery' ), false, '295', true );
        wp_enqueue_script( 'wcqi-js' );
      }
    }
  }

}
add_action('wp_enqueue_scripts', 'kadence_scripts', 100);

/**
 * Add Respond.js for IE8 support of media queries
 */
function virtue_ie_support_header() {
    echo '<!--[if lt IE 9]>'. "\n";
    echo '<script src="' . esc_url( get_template_directory_uri() . '/assets/js/vendor/respond.min.js' ) . '"></script>'. "\n";
    echo '<![endif]-->'. "\n";
}
add_action( 'wp_head', 'virtue_ie_support_header', 15 );

function kadence_google_analytics() { 
  global $virtue_premium; 
  if(isset($virtue_premium['google_analytics']) && !empty($virtue_premium['google_analytics'])) { ?>
  <!-- Google Analytics -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', '<?php echo $virtue_premium['google_analytics']; ?>', 'auto');
ga('send', 'pageview');
</script>
<!-- End Google Analytics -->
  <?php
  }
}
add_action('wp_head', 'kadence_google_analytics', 20);
