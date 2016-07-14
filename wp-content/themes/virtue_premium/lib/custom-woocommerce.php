<?php 
/*-----------------------------------------------------------------------------------*/
/* This theme supports WooCommerce */
/*-----------------------------------------------------------------------------------*/

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
  add_theme_support( 'woocommerce' );
}
/*-----------------------------------------------------------------------------------*/
/* WooCommerce Functions */
/*-----------------------------------------------------------------------------------*/

if (class_exists('woocommerce')) {
  add_filter( 'woocommerce_enqueue_styles', '__return_false' );
  // Disable WooCommerce Lightbox
  update_option( 'woocommerce_enable_lightbox', false );

  // Makes the product finder plugin work.
    remove_action( 'template_redirect' , array( 'WooCommerce_Product_finder' , 'load_template' ) );
    if(class_exists('WC_PDF_Product_Vouchers')) {
      add_filter('template_include', 'kt_wc_voucher_override', 20);
        function kt_wc_voucher_override($template) {
            $cpt = get_post_type();
            if ($cpt == 'wc_voucher') {
              remove_filter('template_include', array('Kadence_Wrapping', 'wrap'), 101);
            }
            return $template;
        }
    }
}

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_filter( 'archive_woocommerce_short_description', 'wptexturize', 10);
add_filter( 'archive_woocommerce_short_description', 'wpautop', 10);
add_filter( 'archive_woocommerce_short_description', 'shortcode_unautop', 10);
add_filter( 'archive_woocommerce_short_description', 'do_shortcode', 11 );

add_action('kt_afterheader', 'kt_wc_print_notices');
function kt_wc_print_notices() {
  if (class_exists('woocommerce')) {
    if(!is_shop() and !is_woocommerce() and !is_cart() and !is_checkout() and !is_account_page() ) {
      echo '<div class="container">';
      echo do_shortcode( '[woocommerce_messages]' );
      echo '</div>';
    }
  }
}
 
// Set the number of columns to 3
function kad_woocommerce_cross_sells_columns( $columns ) {
  return 3;
}
add_filter( 'woocommerce_cross_sells_columns', 'kad_woocommerce_cross_sells_columns', 10, 1 );

// Limit the number of cross sells displayed to a maximum of 3
function kad_woocommerce_cross_sells_total( $limit ) {
  return 3;
}
add_filter( 'woocommerce_cross_sells_total', 'kad_woocommerce_cross_sells_total', 10, 1 );
// Redefine woocommerce_output_related_products()

function kad_woo_related_products_limit() {
  global $product, $woocommerce;
  $related = $product->get_related(20);
  $args = array(
    'post_type'           => 'product',
    'no_found_rows'       => 1,
    'posts_per_page'      => 8,
    'ignore_sticky_posts'   => 1,
    'orderby'               => 'rand',
    'post__in'              => $related,
    'post__not_in'          => array($product->id)
  );
  return $args;
}
add_filter( 'woocommerce_related_products_args', 'kad_woo_related_products_limit' );

add_filter( 'woocommerce_product_tabs', 'kad_product_video_tab');
function kad_product_video_tab_content() {
  global $post,$virtue_premium; if($videocode = get_post_meta( $post->ID, '_kad_product_video', true )) {
  if(!empty($virtue_premium['video_title_text'])) {$product_video_title = $virtue_premium['video_title_text'];} else {$product_video_title = __('Product Video', 'virtue');}
 echo '<h2>'.$product_video_title.'</h2>';
 echo '<div class="videofit product_video_case">'.$videocode.'</div>';
}
}
function kad_product_video_tab($tabs) {
  global $post, $virtue_premium; if($videocode = get_post_meta( $post->ID, '_kad_product_video', true )) {
    if(!empty($virtue_premium['video_tab_text'])) {$product_video_title = $virtue_premium['video_tab_text'];} else {$product_video_title = __('Product Video', 'virtue');}
 $tabs['video_tab'] = array(
 'title' => $product_video_title,
 'priority' => 50,
 'callback' => 'kad_product_video_tab_content'
 );
}

 return $tabs;
}
// Number of products per page
add_filter('loop_shop_per_page', 'kt_wooframework_products_per_page');
  function kt_wooframework_products_per_page() {
    global $virtue_premium;
    if ( isset( $virtue_premium['products_per_page'] ) && !empty($virtue_premium['products_per_page']) ) {
      return $virtue_premium['products_per_page'];
    }
}

// Display product tabs?
add_action('wp_head','wooframework_tab_check');
if ( ! function_exists( 'wooframework_tab_check' ) ) {
  function wooframework_tab_check() {
    global $virtue_premium;
    if ( isset( $virtue_premium[ 'product_tabs' ] ) && $virtue_premium[ 'product_tabs' ] == "0" ) {
      remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    }
  }
}

// Display related products?
add_action('wp_head','wooframework_related_products');
if ( ! function_exists( 'wooframework_related_products' ) ) {
  function wooframework_related_products() {
    global $virtue_premium;
    if ( isset( $virtue_premium[ 'related_products' ] ) && $virtue_premium[ 'related_products' ] == "0" ) {
      remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
    }
  }
}

// Change the tab title
add_filter( 'woocommerce_product_tabs', 'kad_woo_rename_tabs', 98 );
function kad_woo_rename_tabs( $tabs ) {
 global $virtue_premium; 
  if(!empty($virtue_premium['description_tab_text']) && !empty($tabs['description']['title'])) {$tabs['description']['title'] = $virtue_premium['description_tab_text'];}
  if(!empty($virtue_premium['additional_information_tab_text']) && !empty($tabs['additional_information']['title'])) {$tabs['additional_information']['title'] = $virtue_premium['additional_information_tab_text'];}
  if(!empty($virtue_premium['reviews_tab_text']) && !empty($tabs['reviews']['title'])) {$tabs['reviews']['title'] = $virtue_premium['reviews_tab_text'];}
 
  return $tabs;
}
// Change the tab description heading
add_filter( 'woocommerce_product_description_heading', 'kad_description_tab_heading', 10, 1 );
function kad_description_tab_heading( $title ) {
  global $virtue_premium; 
  if(!empty($virtue_premium['description_header_text'])) {$title = $virtue_premium['description_header_text'];}
  return $title;
}
// Change the tab aditional info heading
add_filter( 'woocommerce_product_additional_information_heading', 'kad_additional_information_tab_heading', 10, 1 );
function kad_additional_information_tab_heading( $title ) {
  global $virtue_premium; 
  if(!empty($virtue_premium['additional_information_header_text'])) {$title = $virtue_premium['additional_information_header_text'];}
  return $title;
}

add_filter( 'woocommerce_product_tabs', 'kad_woo_reorder_tabs', 98 );
function kad_woo_reorder_tabs( $tabs ) {
  global $virtue_premium; 
  if(isset($virtue_premium['ptab_description'])) {$dpriority = $virtue_premium['ptab_description'];} else {$dpriority = 10;}
  if(isset($virtue_premium['ptab_additional'])) {$apriority = $virtue_premium['ptab_additional'];} else {$apriority = 20;}
  if(isset($virtue_premium['ptab_reviews'])) {$rpriority = $virtue_premium['ptab_reviews'];} else {$rpriority = 30;}
  if(isset($virtue_premium['ptab_video'])) {$vpriority = $virtue_premium['ptab_video'];} else {$vpriority = 40;}
 
  if(!empty($tabs['description'])) $tabs['description']['priority'] = $dpriority;      // Description
  if(!empty($tabs['additional_information'])) $tabs['additional_information']['priority'] = $apriority; // Additional information 
  if(!empty($tabs['reviews'])) $tabs['reviews']['priority'] = $rpriority;     // Reviews 
  if(!empty($tabs['video_tab'])) $tabs['video_tab']['priority'] = $vpriority;      // Video second
 
  return $tabs;
}

add_filter('loop_shop_columns', 'kad_loop_columns');
  function kad_loop_columns() {
    global $virtue_premium;
    if(isset($virtue_premium['product_shop_layout']) && !empty($virtue_premium['product_shop_layout'])) {
      return $virtue_premium['product_shop_layout'];
    } else {
      return 4;
    }
}
// Turning off for the time being, causing issues with cart widget
add_filter('add_to_cart_fragments', 'kad_woocommerce_header_add_to_cart_fragment');
function kad_woocommerce_header_add_to_cart_fragment( $fragments ) {
    global $woocommerce, $virtue_premium;
    ob_start(); ?>
    <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'virtue'); ?>">
        <i class="icon-basket" style="padding-right:5px;"></i> 
        <?php if(!empty($virtue_premium['cart_placeholder_text'])) {
            echo $virtue_premium['cart_placeholder_text'];
          } else {
            echo __('Your Cart', 'virtue');
            }  ?> 
            <span class="kad-cart-dash">-</span>
            <?php if ( WC()->cart->tax_display_cart == 'incl' ) {
              echo WC()->cart->get_cart_subtotal(); 
            } else {
              echo WC()->cart->get_cart_total();
            }
              ?>
    </a>
    <?php
    $fragments['a.cart-contents'] = ob_get_clean();
    return $fragments;
}


// Add the custom tabs

function kad_custom_tab_01($tabs) {
  global $post; 
  $tab_content = apply_filters('kadence_custom_woo_tab_01_content', get_post_meta( $post->ID, '_kad_tab_content_01', true ) );
  if(!empty( $tab_content) ) {
    $tab_title = get_post_meta( $post->ID, '_kad_tab_title_01', true );
    $tab_priority = get_post_meta( $post->ID, '_kad_tab_priority_01', true ); 
    if(!empty($tab_title)) {$product_tab_title = $tab_title;} else {$product_tab_title = __('Custom Tab', 'virtue');}
    if(!empty($tab_priority)) {$product_tab_priority = esc_attr($tab_priority);} else {$product_tab_priority = 45;}
   $tabs['kad_custom_tab_01'] = array(
   'title' => apply_filters('kadence_custom_woo_tab_01_title', $product_tab_title),
   'priority' => apply_filters('kadence_custom_woo_tab_01_priority', $product_tab_priority),
   'callback' => 'kad_product_custom_tab_content_01'
   );
  }

 return $tabs;
}
function kad_product_custom_tab_content_01() {
   global $post; $tab_content_01 = wpautop(get_post_meta( $post->ID, '_kad_tab_content_01', true ));
   echo do_shortcode('<div class="product_custom_content_case">'.apply_filters('kadence_custom_woo_tab_01_content', __($tab_content_01) ).'</div>');
}
function kad_custom_tab_02($tabs) {
  global $post;
  $tab_content = apply_filters('kadence_custom_woo_tab_02_content', get_post_meta( $post->ID, '_kad_tab_content_02', true ) );
   if(!empty($tab_content) ) {
    $tab_title = get_post_meta( $post->ID, '_kad_tab_title_02', true );
    $tab_priority = get_post_meta( $post->ID, '_kad_tab_priority_02', true ); 
    if(!empty($tab_title)) {$product_tab_title = $tab_title;} else {$product_tab_title = __('Custom Tab', 'virtue');}
    if(!empty($tab_priority)) {$product_tab_priority = esc_attr($tab_priority);} else {$product_tab_priority = 50;}
   $tabs['kad_custom_tab_02'] = array(
   'title' => apply_filters('kadence_custom_woo_tab_02_title', $product_tab_title),
   'priority' => apply_filters('kadence_custom_woo_tab_02_priority', $product_tab_priority),
   'callback' => 'kad_product_custom_tab_content_02'
   );
  }

 return $tabs;
}
function kad_product_custom_tab_content_02() {
   global $post; $tab_content_02 = wpautop(get_post_meta( $post->ID, '_kad_tab_content_02', true ));
   echo do_shortcode('<div class="product_custom_content_case">'.apply_filters('kadence_custom_woo_tab_02_content', __($tab_content_02) ).'</div>');

}
function kad_custom_tab_03($tabs) {
  global $post;
  $tab_content = apply_filters('kadence_custom_woo_tab_03_content', get_post_meta( $post->ID, '_kad_tab_content_03', true ) );
  if(!empty( $tab_content) ) {
    $tab_title = get_post_meta( $post->ID, '_kad_tab_title_03', true );
    $tab_priority = get_post_meta( $post->ID, '_kad_tab_priority_03', true ); 
    if(!empty($tab_title)) {$product_tab_title = $tab_title;} else {$product_tab_title = __('Custom Tab', 'virtue');}
    if(!empty($tab_priority)) {$product_tab_priority = esc_attr($tab_priority);} else {$product_tab_priority = 55;}
   $tabs['kad_custom_tab_03'] = array(
   'title' => apply_filters('kadence_custom_woo_tab_03_title', $product_tab_title ),
   'priority' => apply_filters('kadence_custom_woo_tab_03_priority', $product_tab_priority),
   'callback' => 'kad_product_custom_tab_content_03'
   );
  }

 return $tabs;
}
function kad_product_custom_tab_content_03() {
   global $post; $tab_content_03 = wpautop(get_post_meta( $post->ID, '_kad_tab_content_03', true ));
   echo do_shortcode('<div class="product_custom_content_case">'.apply_filters('kadence_custom_woo_tab_03_content', __($tab_content_03) ).'</div>');
}


add_action( 'init', 'kt_woo_custom_tab_init' );
function kt_woo_custom_tab_init() {
global $virtue_premium;
 if ( isset( $virtue_premium['custom_tab_01'] ) && $virtue_premium['custom_tab_01'] == 1 ) {
add_filter( 'woocommerce_product_tabs', 'kad_custom_tab_01');
}
if ( isset( $virtue_premium['custom_tab_02'] ) && $virtue_premium['custom_tab_02'] == 1 ) {
add_filter( 'woocommerce_product_tabs', 'kad_custom_tab_02');
}
if ( isset( $virtue_premium['custom_tab_03'] ) && $virtue_premium['custom_tab_03'] == 1 ) {
add_filter( 'woocommerce_product_tabs', 'kad_custom_tab_03');
}
}

// Shop Pages

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'kt_woocommerce_template_loop_product_title', 10);
function kt_woocommerce_template_loop_product_title() {
  echo '<h5>'.get_the_title().'</h5>';
}

//Product Pages

if ( ! function_exists( 'kt_wc_radio_variation_attribute_options' ) ) {
  function kt_wc_radio_variation_attribute_options( $args = array() ) {
    $args = wp_parse_args( $args, array(
      'options'          => false,
      'attribute'        => false,
      'product'          => false,
      'selected'         => false,
      'name'             => '',
      'id'               => ''
    ) );
    $options   = $args['options'];
    $product   = $args['product'];
    $attribute = $args['attribute'];
    $name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
    $id        = $args['id'] ? $args['id'] : sanitize_title( $attribute );
    if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
      $attributes = $product->get_variation_attributes();
      $options    = $attributes[ $attribute ];
    }
    echo '<fieldset id="' . esc_attr( $id ) .'" class="kad_radio_variations" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';
    if ( ! empty( $options ) ) {
      if ( $product && taxonomy_exists( $attribute ) ) {
        // Get terms if this is a taxonomy - ordered. We need the names too.
        $terms = wc_get_product_terms( $product->id, $attribute, array( 'fields' => 'all' ) );
        foreach ( $terms as $term ) {
          if ( in_array( $term->slug, $options ) ) {
            echo '<label for="'. esc_attr( sanitize_title($name) ) . esc_attr( $term->slug ) . '"><input type="radio" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" value="' . esc_attr( $term->slug ) . '" ' . checked( sanitize_title( $args['selected'] ), $term->slug, false ) . ' id="'. esc_attr( sanitize_title($name) ) . esc_attr( $term->slug ) . '" name="'. sanitize_title($name).'">' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</label>';
          }
        }
      } else {
        foreach ( $options as $option ) {
          // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
          //$selected = sanitize_title( $args['selected'] ) === $args['selected'] ? checked( $args['selected'], sanitize_title( $option ), false ) : checked( $args['selected'], $option, false );
          echo '<label for="'. esc_attr( sanitize_title($name) ) . esc_attr( sanitize_title( $option ) ) .'"><input type="radio" value="' . esc_attr( $option ) . '" ' . checked( $args['selected'], $option, false ) . ' id="'. esc_attr( sanitize_title($name) ) . esc_attr( sanitize_title( $option ) ) .'" name="'. sanitize_title($name).'">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</label>';
        }
      }
    }
    echo '</fieldset>';
  }
}

remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
add_action( 'woocommerce_single_variation', 'kt_woocommerce_single_variation', 10 );
add_action( 'woocommerce_single_variation', 'kt_woocommerce_single_variation_add_to_cart_button', 20 );

if ( ! function_exists( 'kt_woocommerce_single_variation_add_to_cart_button' ) ) {
  /**
   * Output the add to cart button for variations.
   */
  function kt_woocommerce_single_variation_add_to_cart_button() {
    global $product;
    ?>
    <div class="variations_button">
      <?php woocommerce_quantity_input( array( 'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 ) ); ?>
      <button type="submit" class="kad_add_to_cart headerfont kad-btn kad-btn-primary single_add_to_cart_button"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
      <input type="hidden" name="add-to-cart" value="<?php echo absint( $product->id ); ?>" />
      <input type="hidden" name="product_id" value="<?php echo absint( $product->id ); ?>" />
      <input type="hidden" name="variation_id" class="variation_id" value="" />
    </div>
    <?php
  }
}

if ( ! function_exists( 'kt_woocommerce_single_variation' ) ) {
  /**
   * Output placeholders for the single variation.
   */
  function kt_woocommerce_single_variation() {
    echo '<div class="single_variation headerfont"></div>';
  }
}

// Shop Page Image
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'kt_woocommerce_template_loop_product_thumbnail', 10 );
function kt_woocommerce_template_loop_product_thumbnail() {
global $product, $woocommerce_loop, $virtue_premium, $post;

$product_column = $woocommerce_loop['columns'];
if ($product_column == '1') {$productimgwidth = 300;}
     else if ($product_column == '2') {$productimgwidth = 300;} 
                    else if ($product_column == '3'){  $productimgwidth = 400;} 
                    else if ($product_column == '6'){  $productimgwidth = 240;} 
                    else if ($product_column == '5'){ $productimgwidth = 240;} 
                    else { $productimgwidth = 300;}

  if(isset($virtue_premium['product_img_resize']) && $virtue_premium['product_img_resize'] == 0) {
  $resizeimage = 0;
} else {
  $resizeimage = 1;
    if(isset($virtue_premium['shop_img_ratio'])) {$img_ratio = $virtue_premium['shop_img_ratio'];} else {$img_ratio = 'square';}
    if($img_ratio == 'portrait') {
          $tempproductimgheight = $productimgwidth * 1.35;
          $productimgheight = floor($tempproductimgheight);
    } else if($img_ratio == 'landscape') {
          $tempproductimgheight = $productimgwidth / 1.35;
          $productimgheight = floor($tempproductimgheight);
    } else if($img_ratio == 'widelandscape') {
          $tempproductimgheight = $productimgwidth / 2;
          $productimgheight = floor($tempproductimgheight);
    } else {
          $productimgheight = $productimgwidth;
    }
}
if(isset($virtue_premium['product_img_flip']) && $virtue_premium['product_img_flip'] == 0) {
  $productimgflip = 0;
} else {
  $productimgflip = 1;
}

  if($productimgflip == 1 && $resizeimage == 1) { 

      // Check for an image to flip to first //
      $attachment_ids = $product->get_gallery_attachment_ids();
      if ( !empty( $attachment_ids ) ) {
            $flipclass = "kad-product-flipper";
      } else {
            $flipclass = "kad-product-noflipper";
      }
      echo '<div class="'.$flipclass.'">';
        if ( has_post_thumbnail() ) {
          $image_id = get_post_thumbnail_id( $post->ID );
          $product_image_array = wp_get_attachment_image_src( $image_id, 'full' ); 
          $product_image_url = $product_image_array[0]; 
          // Make sure there is a copped image to output
          $img_src = aq_resize($product_image_url, $productimgwidth, $productimgheight, true);
          if(empty($img_src)) {$img_src = $product_image_url; }
          // Get srcset
          $img_srcset = kt_get_srcset($productimgwidth, $productimgheight, $product_image_url, $image_id);
          // Get alt and fall back to title if no alt
          $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
          if(empty($alt_text)) {$alt_text = get_the_title();}
          ?> 
              <div class="kad_img_flip image_flip_front">
                  <img width="<?php echo esc_attr($productimgwidth);?>" height="<?php echo esc_attr($productimgheight);?>" 
                  src="<?php echo esc_url($img_src);?>"
                  <?php if(!empty($img_srcset)) { ?>
                  srcset="<?php echo esc_attr( $img_srcset ); ?>"
                  sizes="(max-width: <?php echo esc_attr($productimgwidth);?>px) 100vw, <?php echo esc_attr($productimgwidth);?>px"
                <?php } ?>
                  class="attachment-shop_catalog size-<?php echo esc_attr($productimgwidth.'x'.$productimgheight);?> wp-post-image" 
                  alt="<?php echo esc_attr($alt_text); ?>">
              </div>
              
          <?php }
            if ( !empty( $attachment_ids) ) {
              $secondary_image_id = $attachment_ids['0'];
              $second_product_image_url = wp_get_attachment_image_src( $secondary_image_id, 'full');
              $second_product_image_url = $second_product_image_url[0];
              // Make sure there is a copped image to output
              $second_image_product = aq_resize($second_product_image_url, $productimgwidth, $productimgheight, true);
              if(empty($second_image_product)) {$second_image_product = wp_get_attachment_image($secondary_image_id, $productimgwidth, $productimgheight);}
              // Get srcset
              $second_image_srcset = kt_get_srcset( $productimgwidth, $productimgheight, $second_product_image_url, $secondary_image_id);
              // Get alt and fall back to title if no alt
              $alt_text = get_post_meta($secondary_image_id, '_wp_attachment_image_alt', true);
              if(empty($alt_text)) {$alt_text = get_the_title();}

              ?>
              <div class="kad_img_flip image_flip_back">
                <img width="<?php echo esc_attr($productimgwidth);?>" height="<?php echo esc_attr($productimgheight);?>" 
                src="<?php echo esc_attr($second_image_product);?>"
                <?php if(!empty($second_image_srcset)) { ?>
                srcset="<?php echo esc_attr( $second_image_srcset ); ?>"
                sizes="(max-width: <?php echo esc_attr($productimgwidth);?>px) 100vw, <?php echo esc_attr($productimgwidth);?>px"
                <?php } ?>
                class="attachment-shop_catalog size-<?php echo esc_attr($productimgwidth.'x'.$productimgheight);?> wp-post-image" 
                alt="<?php echo esc_attr($alt_text); ?>">
              </div>
          <?php  } 
          echo '</div>';
      } else if ( $resizeimage == 1 ) {
          echo '<div class="kad-product-noflipper">';
          if ( has_post_thumbnail() ) {
          $image_id = get_post_thumbnail_id( $post->ID );
          $product_image_array = wp_get_attachment_image_src( $image_id, 'full' ); 
          $product_image_url = $product_image_array[0]; 
          // Make sure there is a copped image to output
          $img_src = aq_resize($product_image_url, $productimgwidth, $productimgheight, true);
          if(empty($img_src)) {$img_src = $product_image_url; }
          // Get srcset
          $img_srcset = kt_get_srcset( $productimgwidth, $productimgheight, $product_image_url, $image_id);
          // Get alt and fall back to title if no alt
          $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
          if(empty($alt_text)) {$alt_text = get_the_title();}
                ?> 
                <img width="<?php echo esc_attr($productimgwidth);?>" height="<?php echo esc_attr($productimgheight);?>" 
                src="<?php echo esc_url($img_src);?>"
                <?php if(!empty($img_srcset)) { ?>
                  srcset="<?php echo esc_attr( $img_srcset ); ?>"
                  sizes="(max-width: <?php echo esc_attr($productimgwidth);?>px) 100vw, <?php echo esc_attr($productimgwidth);?>px"
                <?php } ?>
                class="attachment-shop_catalog size-<?php echo esc_attr($productimgwidth.'x'.$productimgheight);?> wp-post-image" 
                alt="<?php echo esc_attr($alt_text); ?>">
              <?php }
              echo '</div>';
      } else { 
        echo '<div class="kad-woo-image-size">';
        echo woocommerce_template_loop_product_thumbnail();
        echo '</div>';
         }
}

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );

remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
add_action( 'woocommerce_shop_loop_subcategory_title', 'kt_woocommerce_template_loop_category_title', 10 );

 function kt_woocommerce_template_loop_category_title( $category ) {
        ?>
        <h5>
            <?php
                echo $category->name;

                if ( $category->count > 0 )
                    echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . $category->count . ')</mark>', $category );
            ?>
        </h5>
        <?php
    }


function kt_add_class_woocommerce_loop_add_to_cart_link($array, $product) {
  $array['class'] .= ' kad-btn headerfont kad_add_to_cart';
  return $array;
}   
add_filter('woocommerce_loop_add_to_cart_args', 'kt_add_class_woocommerce_loop_add_to_cart_link', 10, 2);

remove_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
remove_action( 'woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10 );
add_action( 'woocommerce_before_subcategory', 'kt_woocommerce_template_loop_category_link_open', 10 );
add_action( 'woocommerce_after_subcategory', 'kt_woocommerce_template_loop_category_link_close', 10 );

function kt_woocommerce_template_loop_category_link_open( $category ) {
    echo '<a href="' . get_term_link( $category->slug, 'product_cat' ) . '">';
}
function kt_woocommerce_template_loop_category_link_close() {
    echo '</a>';
}