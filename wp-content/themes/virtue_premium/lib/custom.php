<?php
/**
 * Custom functions
 */

function kf_reflush_rules() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}
add_action( 'after_switch_theme', 'kf_reflush_rules' );

add_action( 'kt_beforeheader', 'revolutionslider_top', 1 );
function revolutionslider_top() {
  if ( is_front_page() ){
  global $virtue_premium;
  if(isset($virtue_premium['above_header_slider']) && $virtue_premium['above_header_slider'] == 1) {
    if(isset($virtue_premium['choose_slider']) && ($virtue_premium['choose_slider'] == 'ktslider' || $virtue_premium['choose_slider'] == 'cyclone' || $virtue_premium['choose_slider'] == 'rev')) {
    if($virtue_premium['choose_slider'] == 'rev') {
      echo '<div class="kad_fullslider">';
      if( function_exists('putRevSlider') ) {
      putRevSlider( $virtue_premium['rev_slider'] );
      }
    }
    else if($virtue_premium['choose_slider'] == 'ktslider') {
      echo '<div class="kad_fullslider">';
      echo do_shortcode('[kadence_slider id='.$virtue_premium['kt_slider'].']');
    } 
    else if($virtue_premium['choose_slider'] == 'cyclone') {
      echo '<div class="kad_fullslider">';
      echo do_shortcode( $virtue_premium['home_cyclone_slider'] );
    }
      if(isset($virtue_premium['above_header_slider_arrow']) && $virtue_premium['above_header_slider_arrow'] == 1) {
        echo '<div class="kad_fullslider_arrow"><a href="#kad-banner"><i class="icon-arrow-down"></i></a></div>';
      }
      echo '</div>';
      if(isset($virtue_premium['header_style']) && $virtue_premium['header_style'] == 'shrink') {
        $head_height = $virtue_premium['header_height']/2;
        echo '<style type="text/css" media="screen">@media (min-width: 992px) {.kad-header-style-three #kad-shrinkheader, .kad-header-style-three #logo a.brand, .kad-header-style-three #logo #thelogo, .kad-header-style-three #nav-main ul.sf-menu > li > a {height:'.$head_height.'px !important;line-height: '.$head_height.'px !important;}.kad-header-style-three #thelogo img {max-height: '.$head_height.'px !important;}}</style>';
      }
    }
  }
}
}
add_action( 'kt_beforeheader', 'featureslider_top', 1 );
function featureslider_top() {
  if ( is_page_template('page-feature.php') || is_page_template('page-feature-sidebar.php') ){
  global $post, $virtue_premium;
  $slider = get_post_meta( $post->ID, '_kad_page_head', true ); 
  $above = get_post_meta( $post->ID, '_kad_shortcode_above_header', true );
  $arrow = get_post_meta( $post->ID, '_kad_shortcode_above_header_arrow', true ); 
  if(isset($above) && $above == 'on') {
    if(isset($slider) && ($slider == 'ktslider' || $slider == 'cyclone' || $slider == 'rev')) {
    if($slider == 'rev') {
      echo '<div class="kad_fullslider">';
      get_template_part('templates/rev', 'slider');
    }
    else if($slider == 'ktslider') {
      echo '<div class="kad_fullslider">';
      get_template_part('templates/cyclone', 'slider');
    } 
    else if($slider == 'cyclone') {
      echo '<div class="kad_fullslider">';
      get_template_part('templates/cyclone', 'slider');
    }
      if(isset($arrow) && $arrow == "on") {
        echo '<div class="kad_fullslider_arrow"><a href="#kad-banner"><i class="icon-arrow-down"></i></a></div>';
      }
      echo '</div>';
      if(isset($virtue_premium['header_style']) && $virtue_premium['header_style'] == 'shrink') {
        $head_height = $virtue_premium['header_height']/2;
           echo '<style type="text/css" media="screen">@media (min-width: 992px) {.kad-header-style-three #kad-shrinkheader, .kad-header-style-three #logo a.brand, .kad-header-style-three #logo #thelogo, .kad-header-style-three #nav-main ul.sf-menu > li > a {height:'.$head_height.'px !important;line-height: '.$head_height.'px !important;}.kad-header-style-three #thelogo img {max-height: '.$head_height.'px !important;}}</style>';
      }
    }
  }
}
}

  add_filter('kadence_wrap_base', 'kadence_wrap_base_kadslider'); // Add our function to the roots_wrap_base filter

  function kadence_wrap_base_kadslider($templates) {
    $cpt = get_post_type(); // Get the current post type
    if ($cpt == 'kadslider') {
       array_unshift($templates, 'base-kadslider.php'); // Shift the template to the front of the array
    }
    return $templates; // Return our modified array with base-$cpt.php at the front of the queue
  }

// Add support for page builder
function kadence_siteoriginpanels_row_attributes($attr, $row) {
  if(!empty($row['style']['class'])) {
    if(empty($attr['style'])) $attr['style'] = '';
    $attr['style'] .= 'margin-bottom: 0px;';
    $attr['style'] .= 'margin-left: 0px;';
    $attr['style'] .= 'margin-right: 0px;';
  }

  return $attr;
}
add_filter('siteorigin_panels_row_attributes', 'kadence_siteoriginpanels_row_attributes', 10, 2);
function kadence_siteoriginpanels_row_attributes_content($attr, $row) {
  if(!empty($row['style']['class']) && $row['style']['class'] =="wide-content") {
    if(empty($attr['style'])) $attr['style'] = '';
    $attr['style'] .= 'margin-left: 0px;';
    $attr['style'] .= 'margin-right: 0px;';
  }

  return $attr;
}
//add_filter('siteorigin_panels_row_attributes', 'kadence_siteoriginpanels_row_attributes_content', 10, 2);
function kad_panels_row_background_styles($fields) {
  $fields['padding_top'] = array(
        'name'      => __('Padding Top', 'virtue'),
        'type'      => 'measurement',
        'group'     => 'layout',
        'priority'  => 8,
  );
  $fields['padding_bottom'] = array(
        'name'      => __('Padding Bottom', 'virtue'),
        'type'      => 'measurement',
        'group'     => 'layout',
        'priority'  => 8.5,
  );
  $fields['padding_left'] = array(
        'name'      => __('Padding Left', 'virtue'),
        'type'      => 'measurement',
        'group'     => 'layout',
        'priority'  => 9,
      );
  $fields['padding_right'] = array(
        'name'      => __('Padding Right', 'virtue'),
        'type'      => 'measurement',
        'group'     => 'layout',
        'priority'  => 9,
      );
  $fields['background_image'] = array(
        'name'      => __('Background Image', 'virtue'),
        'group'     => 'design',
        'type'      => 'image',
        'priority'  => 5,
      );
  $fields['background_image_position'] = array(
        'name'      => __('Background Image Position', 'virtue'),
        'type'      => 'select',
        'group'     => 'design',
        'default'   => 'center top',
        'priority'  => 6,
        'options'   => array(
               "left top"       => __("Left Top", "virtue"),
               "left center"    => __("Left Center", "virtue"),
               "left bottom"    => __("Left Bottom", "virtue"),
               "center top"     => __("Center Top", "virtue"),
               "center center"  => __("Center Center", "virtue"),
               "center bottom"  => __("Center Bottom", "virtue"),
               "right top"      => __("Right Top", "virtue"),
               "right center"   => __("Right Center", "virtue"),
               "right bottom"   => __("Right Bottom", "virtue")
                ),
      );
  $fields['background_image_style'] = array(
        'name'      => __('Background Image Style', 'virtue'),
        'type'      => 'select',
        'group'     => 'design',
        'default'   => 'center top',
        'priority'  => 6,
        'options'   => array(
             "cover"      => __("Cover", "virtue"),
             "parallax"   => __("Parallax", "virtue"),
             "no-repeat"  => __("No Repeat", "virtue"),
             "repeat"     => __("Repeat", "virtue"),
             "repeat-x"   => __("Repeat-X", "virtue"),
             "repeat-y"   => __("Repeat-y", "virtue"),
              ),
        );
  $fields['border_top'] = array(
        'name'      => __('Border Top Size', 'virtue'),
        'type'      => 'measurement',
        'group'     => 'design',
        'priority'  => 8,
  );
  $fields['border_top_color'] = array(
        'name'      => __('Border Top Color', 'virtue'),
        'type'      => 'color',
        'group'     => 'design',
        'priority'  => 8.5,
      );
  $fields['border_bottom'] = array(
        'name'      => __('Border Bottom Size', 'virtue'),
        'type'      => 'measurement',
        'group'     => 'design',
        'priority'  => 9,
  );
  $fields['border_bottom_color'] = array(
        'name' => __('Border Bottom Color', 'virtue'),
        'type' => 'color',
        'group' => 'design',
        'priority' => 9.5,
  );
  return $fields;
}
add_filter('siteorigin_panels_row_style_fields', 'kad_panels_row_background_styles');
function kad_panels_remove_row_background_styles($fields) {
 unset( $fields['background_image_attachment'] );
 unset( $fields['background_display'] );
 unset( $fields['padding'] );
 unset( $fields['border_color'] );
 return $fields;
}
add_filter('siteorigin_panels_row_style_fields', 'kad_panels_remove_row_background_styles');

function kad_panels_row_background_styles_attributes($attributes, $args) {

  if(!empty($args['background_image'])) {
    $url = wp_get_attachment_image_src( $args['background_image'], 'full' );

    if(empty($url) || $url[0] == site_url() ) {
        $attributes['style'] .= 'background-image: url(' . $args['background_image'] . ');';
      } else {
        $attributes['style'] .= 'background-image: url(' . $url[0] . ');';
      }
      if(!empty($args['background_image_style'])) {
            switch( $args['background_image_style'] ) {
              case 'no-repeat':
                $attributes['style'] .= 'background-repeat: no-repeat;';
                break;
              case 'repeat':
                $attributes['style'] .= 'background-repeat: repeat;';
                break;
              case 'repeat-x':
                $attributes['style'] .= 'background-repeat: repeat-x;';
                break;
              case 'repeat-y':
                $attributes['style'] .= 'background-repeat: repeat-y;';
                break;
              case 'cover':
                $attributes['style'] .= 'background-size: cover;';
                break;
              case 'parallax':
                $attributes['class'][] .= 'kt-panel-row-parallax-stellar';
                $attributes['data-stellar-background-ratio'] = '0.5';
                break;
            }
        }

  }
  if( (!empty( $args['row_stretch']) && $args['row_stretch'] == 'full') || (!empty( $args['row_stretch']) && $args['row_stretch'] == 'full-stretched' )  ) {
    $attributes['style'] .= 'visibility: hidden;';
  }
  if(!empty( $args['row_stretch']) && $args['row_stretch'] == 'full') {
    $attributes['class'][] .= 'kt-panel-row-stretch';
  }
  if(!empty( $args['row_stretch']) && $args['row_stretch'] == 'full-stretched') {
    $attributes['class'][] .= 'kt-panel-row-full-stretch';
  }
  if(!empty($args['padding_top'])) {
    if( function_exists('is_numeric' ) ) {
      if (is_numeric($args['padding_top'])) {
        $attributes['style'] .= 'padding-top: '.esc_attr($args['padding_top']).'px; ';
      } else {
         $attributes['style'] .= 'padding-top: '.esc_attr($args['padding_top']).'; ';
      }
    } else {
       $attributes['style'] .= 'padding-top: '.esc_attr($args['padding_top']).'; ';
    }
  }
  if(!empty($args['padding_bottom'])){
    if( function_exists('is_numeric' ) ) {
      if (is_numeric($args['padding_bottom'])) {
        $attributes['style'] .= 'padding-bottom: '.esc_attr($args['padding_bottom']).'px; ';
      } else {
        $attributes['style'] .= 'padding-bottom: '.esc_attr($args['padding_bottom']).'; ';
      }
    } else {
      $attributes['style'] .= 'padding-bottom: '.esc_attr($args['padding_bottom']).'; ';
    }
 }
 if(!empty($args['padding_left'])){
   $attributes['style'] .= 'padding-left: '.esc_attr($args['padding_left']).'; ';
 }
 if(!empty($args['padding_right'])){
   $attributes['style'] .= 'padding-right: '.esc_attr($args['padding_right']).'; ';
 }
 if(!empty($args['border_top'])){
   $attributes['style'] .= 'border-top: '.esc_attr($args['border_top']).' solid; ';
 }
 if(!empty($args['border_top_color'])){
   $attributes['style'] .= 'border-top-color: '.$args['border_top_color'].'; ';
 }
 if(!empty($args['border_bottom'])){
   $attributes['style'] .= 'border-bottom: '.esc_attr($args['border_bottom']).' solid; ';
 }
  if(!empty($args['border_bottom_color'])){
   $attributes['style'] .= 'border-bottom-color: '.$args['border_bottom_color'].'; ';
 }

  return $attributes;
}
add_filter('siteorigin_panels_row_style_attributes', 'kad_panels_row_background_styles_attributes', 10, 2);

remove_action( 'siteorigin_panels_before_interface', 'siteorigin_panels_update_notice'); 

// Add support for qtranslate
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active('qtranslate/qtranslate.php') || is_plugin_active('mqtranslate/mqtranslate.php') ) {
    add_action('portfolio-type_add_form',  'qtrans_modifyTermFormFor');
    add_action('portfolio-type_edit_form',   'qtrans_modifyTermFormFor');
    add_action('product_cat_add_form',   'qtrans_modifyTermFormFor');
    add_action('product_cat_edit_form',  'qtrans_modifyTermFormFor');
    add_action('product_tag_add_form',   'qtrans_modifyTermFormFor');
    add_action('product_tag_edit_form',  'qtrans_modifyTermFormFor');
    add_filter('woocommerce_cart_item_name', 'qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage', 0);
}

function virtue_prebuilt_page_layouts($layouts){
  $layouts['example-page'] = array (
    'name' => __('Example Page', 'virtue'),
    'widgets' =>
    array(
      0 =>
      array(
        'title' => 'Easy to Customize',
        'description' => "Vestibulum pharetra pellentesque elit. Donec massa magna, semper nec tincidunt eu, condimentum non arcu. In hac habitasse platea dictumst. Integer ut risus imperdiet, hendrerit nunc nec, viverra velit. Duis ullamcorper sit amet diam in hendrerit. Nunc laoreet tincidunt consequat. Fusce vel odio ut magna vestibulum volutpat luctus a ante. Donec tincidunt ultrices sollicitudin. Phasellus scelerisque congue suscipit.",
        'info_icon' => 'icon-pencil2',
        'image_uri' => '',
        'size' => '20',
        'style' => 'kad-circle-iconclass',
        'color' => '#ffffff',
        'iconbackground' => '#444444',
        'background' => '',
        'info' =>
        array(
          'class' => 'kad_infobox_widget',
          'id' => '1',
          'grid' => '0',
          'cell' => '0',
        ),
      ),
      1 =>
      array(
        'title' => 'Beautiful Layouts',
        'description' => "Vestibulum pharetra pellentesque elit. Donec massa magna, semper nec tincidunt eu, condimentum non arcu. In hac habitasse platea dictumst. Integer ut risus imperdiet, hendrerit nunc nec, viverra velit. Duis ullamcorper sit amet diam in hendrerit. Nunc laoreet tincidunt consequat. Fusce vel odio ut magna vestibulum volutpat luctus a ante. Donec tincidunt ultrices sollicitudin. Phasellus scelerisque congue suscipit.",
        'info_icon' => 'icon-laptop',
        'image_uri' => '',
        'size' => '20',
        'style' => 'kad-circle-iconclass',
        'color' => '#ffffff',
        'iconbackground' => '#444444',
        'background' => '',
        'info' =>
        array(
          'class' => 'kad_infobox_widget',
          'id' => '2',
          'grid' => '0',
          'cell' => '1',
        ),
      ),
      2 =>
      array(
        'title' => 'Tons of Extras',
        'description' => "Vestibulum pharetra pellentesque elit. Donec massa magna, semper nec tincidunt eu, condimentum non arcu. In hac habitasse platea dictumst. Integer ut risus imperdiet, hendrerit nunc nec, viverra velit. Duis ullamcorper sit amet diam in hendrerit. Nunc laoreet tincidunt consequat. Fusce vel odio ut magna vestibulum volutpat luctus a ante. Donec tincidunt ultrices sollicitudin. Phasellus scelerisque congue suscipit.",
        'info_icon' => 'icon-basket',
        'image_uri' => '',
        'size' => '20',
        'style' => 'kad-circle-iconclass',
        'color' => '#ffffff',
        'iconbackground' => '#444444',
        'background' => '',
        'info' =>
        array(
          'class' => 'kad_infobox_widget',
          'id' => '3',
          'grid' => '0',
          'cell' => '2',
        ),
      ),
      3 =>
      array(
        'text' => '<h1 style="color:#fff; font-size:60px; text-align:center;">Like What You See?</h1>',
        'info' =>
        array(
          'class' => 'WP_Widget_Text',
          'id' => '4',
          'grid' => '1',
          'cell' => '0',
        ),
      ),
      4 =>
      array(
        'text' => '<div style="text-align:center">[btn  text="View More" tcolor="#ffffff" link="#" size="large" font="h1-family" icon="icon-arrow-right"]</div>',
        'info' =>
        array(
          'class' => 'WP_Widget_Text',
          'id' => '5',
          'grid' => '1',
          'cell' => '0',
        ),
      ),
      5 =>
      array(
        'title' => __('Latest Posts', 'virtue'),
        'type' => 'post',
        'c_items' => '6',
        'c_columns' => '3',
        'c_cat' => '',
        'c_speed' => '7000',
        'c_scroll' => '',
        'info' =>
        array(
          'class' => 'kad_carousel_widget',
          'id' => '6',
          'grid' => '2',
          'cell' => '0',
        ),
      ),
      6 =>
      array(
        'locationtitle' => 'Kadence Themes',
        'location' => 'Missoula, MT, USA',
        'height' => '400',
        'maptype' => 'ROADMAP',
        'zoom' => '13',
        'info' =>
        array(
          'class' => 'kad_gmap_widget',
          'id' => '7',
          'grid' => '2',
          'cell' => '1',
        ),
      ),
    ),
    'grids' =>
    array(
      0 =>
      array(
        'cells' => '3',
        'style' => '',
      ),
      1 =>
      array(
        'cells' => '1',
        'style' => array(
            'row_stretch'     => 'full',
            'background'      => '#555555',
            'padding_top'     => '80px',
            'padding_bottom'  => '80px',
            'bottom_margin'   => '0px',
          ),
      ),
      2 =>
      array(
        'cells' => '2',
        'style' => array(
        'padding_top' => '30px', 
        'padding_bottom' => '0px',
        ),
      ),
    ),
    'grid_cells' =>
    array(
      0 =>
      array(
        'weight' => '0.3333333333333333',
        'grid' => '0',
      ),
      1 =>
      array(
        'weight' => '0.3333333333333333',
        'grid' => '0',
      ),
      2 =>
      array(
        'weight' => '0.3333333333333333',
        'grid' => '0',
      ),
      3 =>
      array(
        'weight' => '1',
        'grid' => '1',
      ),
      4 =>
      array(
        'weight' => '0.6658461538461539',
        'grid' => '2',
      ),
      5 =>
      array(
        'weight' => '0.33415384615384613',
        'grid' => '2',
      ),
    ),
  );
$layouts['example-page-2'] = array (
    'name' => __('Example Page 2', 'virtue'),
    'widgets' =>
    array(
      0 =>
      array(
        'text' => '[iconbox icon="icon-mobile" iconsize="48px" link="#" color="#444444" background="trasparent" hcolor="#ffffff" hbackground="#00aeff"]<h4>Responsive</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sapien tortor, feugiat non odio quis, volutpat pretium odio. </p>[/iconbox]',
        'info' =>
        array(
          'class' => 'WP_Widget_Text',
          'id' => '1',
          'grid' => '0',
          'cell' => '0',
        ),
      ),
      1 =>
       array(
        'text' => '[iconbox icon="icon-equalizer2" iconsize="48px" link="#" color="#444444" background="trasparent" hcolor="#ffffff" hbackground="#da4b54"]<h4>Tons of Options</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sapien tortor, feugiat non odio quis, volutpat pretium odio. </p>[/iconbox]',
        'info' =>
        array(
          'class' => 'WP_Widget_Text',
          'id' => '2',
          'grid' => '0',
          'cell' => '1',
        ),
      ),
      2 =>
       array(
        'text' => '[iconbox icon="icon-pencil" iconsize="48px" link="#" color="#444444" background="trasparent" hcolor="#ffffff" hbackground="#F76A0C"]<h4>Clean Design</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sapien tortor, feugiat non odio quis, volutpat pretium odio. </p>[/iconbox]',
        'info' =>
        array(
          'class' => 'WP_Widget_Text',
          'id' => '3',
          'grid' => '0',
          'cell' => '2',
        ),
      ),
      3 =>
       array(
        'text' => '[iconbox icon="icon-basket" iconsize="48px" link="#" color="#444444" background="trasparent" hcolor="#ffffff" hbackground="#3E6617"]<h4>eCommerce</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sapien tortor, feugiat non odio quis, volutpat pretium odio. </p>[/iconbox]',
        'info' =>
        array(
          'class' => 'WP_Widget_Text',
          'id' => '4',
          'grid' => '0',
          'cell' => '3',
        ),
      ),
      4 =>
       array(
        'title' => __('Featured Products', 'virtue'),
        'type' => 'featured-products',
        'c_items' => '8',
        'c_columns' => '4',
        'c_cat' => '',
        'c_speed' => '7000',
        'c_scroll' => '',
        'info' =>
        array(
          'class' => 'kad_carousel_widget',
          'id' => '5',
          'grid' => '1',
          'cell' => '0',
        ),
      ),
      5 =>
       array(
        'title' => __('Featured Projects', 'virtue'),
        'type' => 'portfolio',
        'c_items' => '6',
        'c_columns' => '3',
        'c_cat' => '',
        'c_speed' => '7000',
        'c_scroll' => '',
        'info' =>
        array(
          'class' => 'kad_carousel_widget',
          'id' => '6',
          'grid' => '2',
          'cell' => '0',
        ),
      ),
      6 =>
       array(
        'text' => '[blog_posts  items="2" orderby="date"]',
        'info' =>
        array(
          'class' => 'WP_Widget_Text',
          'id' => '7',
          'grid' => '3',
          'cell' => '0',
        ),
      ),
      7 =>
       array(
        'title' => __('Want To See More', 'virtue'),
        'tcolor' => '#ffffff',
        'tsize' => '48',
        'subtitle' => __('Check out the newest demo site', 'virtue'),
        'scolor' => '#ffffff',
        'ssize' => '20',
        'align' => 'center',
        'btn_text' => 'View Now',
        'btn_link' => 'http://themes.kadencethemes.com/virtue-premium-4/',
        'btn_target' => 'true',
        'info' =>
        array(
          'class' => 'kad_calltoaction_widget',
          'id' => '8',
          'grid' => '4',
          'cell' => '0',
        ),
      ),
    ),
    'grids' =>
    array(
      0 =>
      array(
        'cells' => '4',
        'style' => '',
      ),
      1 =>
      array(
        'cells' => '1',
        'style' => '',
      ),
      2 =>
      array(
        'cells' => '1',
        'style' => '',
      ),
      3 =>
      array(
        'cells' => '1',
        'style' => array(
        'padding_top' => '20px',
        'padding_bottom' => '10px',
        ),
      ),
      4 =>
      array(
        'cells' => '1',
        'style' => array(
        'row_stretch' => 'full',
        'background' => '#555555',
        'padding_top' => '45px',
        'padding_bottom' => '45px',
        'bottom_margin'  => '0px',
        ),
      ),
    ),
    'grid_cells' =>
    array(
      0 =>
      array(
        'weight' => '0.25',
        'grid' => '0',
      ),
      1 =>
      array(
        'weight' => '0.25',
        'grid' => '0',
      ),
      2 =>
      array(
        'weight' => '0.25',
        'grid' => '0',
      ),
      3 =>
      array(
        'weight' => '0.25',
        'grid' => '0',
      ),
      4 =>
      array(
        'weight' => '1',
        'grid' => '1',
      ),
      5 =>
      array(
        'weight' => '1',
        'grid' => '2',
      ),
      6 =>
      array(
        'weight' => '1',
        'grid' => '3',
      ),
      7 =>
      array(
        'weight' => '1',
        'grid' => '4',
      ),
    ),
  );

  return $layouts;
}
add_filter('siteorigin_panels_prebuilt_layouts', 'virtue_prebuilt_page_layouts');


add_action( 'init', 'kt_rev_slider_as_theme');
function kt_rev_slider_as_theme() {
  if(is_admin()){ 
    if(function_exists( 'set_revslider_as_theme' )) {
      set_revslider_as_theme();
      kt_hide_revslider_notice();
    }
  }
}
function kt_hide_revslider_notice() {
  global $virtue_premium;
  if(isset($virtue_premium['hide_rev_activation_notice']) && $virtue_premium['hide_rev_activation_notice'] == 1) {
    add_action('admin_head', 'kt_hide_revslider_notice_css');
    update_option('revslider-valid-notice', 'false');
    remove_action('admin_notices', array('RevSliderAdmin', 'add_plugins_page_notices'));
      function kt_hide_revslider_notice_css() {
        echo '<style>
          .toplevel_page_revslider .rs-dashboard {
                display: none;
            }
        </style>';
      }
  }
}

function virtue_img_placeholder_filter_init() {
  global $virtue_premium;
  function virtue_img_placeholder() {
    return apply_filters('kadence_placeholder_image', get_template_directory_uri() . '/assets/img/post_standard.jpg');
  }
  function virtue_img_placeholder_cat() {
    return apply_filters('kadence_placeholder_image_cat', get_template_directory_uri() . '/assets/img/placement.jpg');
  }
  function virtue_img_placeholder_small() {
    return apply_filters('kadence_placeholder_image_small', get_template_directory_uri() . '/assets/img/post_standard-80x50.jpg');
  }
  function virtue_post_default_placeholder() {
    return apply_filters('kadence_post_default_placeholder_image', get_template_directory_uri() . '/assets/img/post_standard.jpg');
  }

  function virtue_post_default_placeholder_override() {
    global $virtue_premium;
    $custom_image = $virtue_premium['post_summery_default_image']['url'];
    return $custom_image;
  }

  if (isset($virtue_premium['post_summery_default_image']) && !empty($virtue_premium['post_summery_default_image']['url'])) {
  add_filter('kadence_placeholder_image_small', 'virtue_post_default_placeholder_override');
  add_filter('kadence_post_default_placeholder_image', 'virtue_post_default_placeholder_override');
  }
}
add_action('init', 'virtue_img_placeholder_filter_init');

function kad_lightbox_text() {
  global $virtue_premium; if(!empty($virtue_premium['lightbox_loading_text'])) {$loading_text = $virtue_premium['lightbox_loading_text'];} else {$loading_text = 'Loading...';}
  if(!empty($virtue_premium['lightbox_of_text'])) {$of_text = $virtue_premium['lightbox_of_text'];} else {$of_text = 'of';}
  if(!empty($virtue_premium['lightbox_error_text'])) {$error_text = $virtue_premium['lightbox_error_text'];} else {$error_text = 'The Image could not be loaded.';}
  echo  '<script type="text/javascript">var light_error = "'.$error_text.'", light_of = "%curr% '.$of_text.' %total%", light_load = "'.$loading_text.'";</script>';
}
add_action('wp_head', 'kad_lightbox_text');

function kad_lightbox_off() {
  global $virtue_premium; 
  if(isset($virtue_premium['kadence_lightbox']) && $virtue_premium['kadence_lightbox'] == 1 ) {
    echo  '<script type="text/javascript">jQuery(document).ready(function ($) {var magnificPopupEnabled = false;$.extend(true, $.magnificPopup.defaults, {disableOn: function() {return false;}});});</script>';
  }
}
add_action('wp_footer', 'kad_lightbox_off');

add_filter('wp_nav_menu_items', 'kt_add_search_form_to_menu', 10, 2);
function kt_add_search_form_to_menu($items, $args) {
  global $virtue_premium, $woocommerce;
 
    if( !($args->theme_location == 'primary_navigation') || (isset($virtue_premium['header_style']) && $virtue_premium['header_style'] == "center" ) )
        return $items;

      ob_start();
      ?>
    <?php if (class_exists('woocommerce'))  {?>
    <?php  if(isset($virtue_premium['menu_cart']) && $virtue_premium['menu_cart'] == '1') { ?>
    <li class="menu-cart-icon-kt sf-dropdown">
    <a class="menu-cart-btn" title="<?php echo __('Your Cart', 'virtue');?>" href="<?php echo esc_url($woocommerce->cart->get_cart_url() ); ?>">
      <div class="kt-cart-container"><i class="icon-cart"></i><span class="kt-cart-total"><?php echo $woocommerce->cart->get_cart_contents_count(); ?></span></div>
    </a>
    <ul id="kad-head-cart-popup" class="sf-dropdown-menu kad-head-cart-popup">
        <div class="kt-header-mini-cart-refreash">
        <?php woocommerce_mini_cart(); 
        do_action( 'kadence_cart_menu_popup_after' ); ?>
        </div>
      </ul>
    </li>
    <?php }
     }?>
    <?php if(isset($virtue_premium['menu_search']) && $virtue_premium['menu_search'] == '1') { ?>
    <li class="menu-search-icon-kt">
      <a class="kt-menu-search-btn collapsed" title="<?php echo __('Search', 'virtue');?>" data-toggle="collapse" data-target="#kad-menu-search-popup">
        <i class="icon-search"></i>
      </a>
        <div id="kad-menu-search-popup" class="search-container container collapse">
          <div class="kt-search-container">
          <?php if(isset($virtue_premium['menu_search_woo']) && $virtue_premium['menu_search_woo'] == '1') { 
            get_product_search_form();
          } else { 
              get_search_form();
            } ?>
          </div>
        </div>
    </li>
    <?php } ?>
   <?php  $output  = ob_get_contents();
        ob_end_clean();
    return $items . $output;
}

add_filter( 'add_to_cart_fragments', 'kt_get_refreshed_fragments' );
 function kt_get_refreshed_fragments($fragments) {
    // Get mini cart
    ob_start();

    woocommerce_mini_cart();

    $mini_cart = ob_get_clean();

    // Fragments and mini cart are returned
    $fragments['div.kt-header-mini-cart-refreash'] ='<div class="kt-header-mini-cart-refreash">' . $mini_cart . '</div>';

    return $fragments;

  }
  add_filter( 'add_to_cart_fragments', 'kt_get_refreshed_fragments_number' );
 function kt_get_refreshed_fragments_number($fragments) {
    global $woocommerce;
    // Get mini cart
    ob_start();

    ?><span class="kt-cart-total"><?php echo WC()->cart->get_cart_contents_count(); ?></span> <?php

    $fragments['span.kt-cart-total'] = ob_get_clean();

    return $fragments;

  }
  
function kad_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}
function kad_shortcode_button_scripts_customizer(){
   wp_enqueue_media();
    wp_enqueue_script('kadwidget_upload', get_template_directory_uri() . '/assets/js/widget_upload.js');
}
add_action( 'customize_controls_enqueue_scripts', 'kad_shortcode_button_scripts_customizer' );

//
function kt_get_srcset($width,$height,$url,$id) {
  if(empty($id) || empty($url)) {
    return;
  }
  
  $image_meta = get_post_meta( $id, '_wp_attachment_metadata', true );
  if(empty($image_meta['file'])){
    return;
  }
  // If possible add in our images on the fly sizes
  $ext = substr($image_meta['file'], strrpos($image_meta['file'], "."));
  $pathflyfilename = str_replace($ext,'-'.$width.'x'.$height.'' . $ext, $image_meta['file']);
  $pathretinaflyfilename = str_replace($ext, '-'.$width.'x'.$height.'@2x' . $ext, $image_meta['file']);
  $flyfilename = basename($image_meta['file'], $ext) . '-'.$width.'x'.$height.'' . $ext;
  $retinaflyfilename = basename($image_meta['file'], $ext) . '-'.$width.'x'.$height.'@2x' . $ext;

  $upload_info = wp_upload_dir();
  $upload_dir = $upload_info['basedir'];

  $flyfile = trailingslashit($upload_dir).$pathflyfilename;
  $retinafile = trailingslashit($upload_dir).$pathretinaflyfilename;
  if(empty($image_meta['sizes']) ){ $image_meta['sizes'] = array();}
    if (file_exists($flyfile)) {
      $kt_add_imagesize = array(
        'kt_on_fly' => array( 
          'file'=> $flyfilename,
          'width' => $width,
          'height' => $height,
          'mime-type' => $image_meta['sizes']['thumbnail']['mime-type'] 
          )
      );
      $image_meta['sizes'] = array_merge($image_meta['sizes'], $kt_add_imagesize);
    }
    if (file_exists($retinafile)) {
      $size = getimagesize( $retinafile );
      if(($size[0] == 2 * $width) && ($size[1] == 2 * $height) ) {
        $kt_add_imagesize_retina = array(
        'kt_on_fly_retina' => array( 
          'file'=> $retinaflyfilename,
          'width' => 2 * $width,
          'height' => 2 * $height,
          'mime-type' => $image_meta['sizes']['thumbnail']['mime-type'] 
          )
        );
        $image_meta['sizes'] = array_merge($image_meta['sizes'], $kt_add_imagesize_retina);
      }
    }
    if(function_exists ( 'wp_calculate_image_srcset') ){
      $output = wp_calculate_image_srcset(array( $width, $height), $url, $image_meta, $id);
    } else {
      $output = '';
    }
    return $output;
}
function kt_get_srcset_output($width,$height,$url,$id) {
    $img_srcset = kt_get_srcset( $width, $height, $url, $id);
    if(!empty($img_srcset) ) {
      $output = 'srcset="'.esc_attr($img_srcset).'" sizes="(max-width: '.esc_attr($width).'px) 100vw, '.esc_attr($width).'px"';
    } else {
      $output = '';
    }
    return $output;
}


///Page Navigation

	function kad_wp_pagenavi() {

  global $wp_query, $wp_rewrite;
  $pages = '';
  $big = 999999999; // need an unlikely integer
  $max = $wp_query->max_num_pages;
  if (!$current = get_query_var('paged')) $current = 1;
  $args['base'] = str_replace($big, '%#%', esc_url( get_pagenum_link( $big ) ) );
  $args['total'] = $max;
  $args['current'] = $current;
  $args['add_args'] = false;

  $total = 1;
  $args['mid_size'] = 3;
  $args['end_size'] = 1;
  $args['prev_text'] = '«';
  $args['next_text'] = '»';
 
  if ($max > 1) echo '<div class="wp-pagenavi">';
 	if ($total == 1 && $max > 1)
 		echo paginate_links($args);
 	if ($max > 1) echo '</div>';
}


/**
 * Schema type
 */
function kadence_html_tag_schema() {
    $schema = 'http://schema.org/';

    if( is_singular( 'post' ) ) {
        $type = "WebPage";
    } else if( is_page_template('page-contact.php') ) {
        $type = 'ContactPage';
    } elseif( is_author() ) {
        $type = 'ProfilePage';
    } elseif( is_search() ) {
        $type = 'SearchResultsPage';
    } else {
        $type = 'WebPage';
    }

    echo apply_filters('kadence_html_schema', 'itemscope="itemscope" itemtype="' .  esc_attr( $schema ) . esc_attr( $type ) . '"' );
}

// Ecerpt Length

function virtue_excerpt($limit) {
   global $virtue_premium; if(!empty($virtue_premium['post_readmore_text'])) {$readmore = $virtue_premium['post_readmore_text'];} else { $readmore =  __('Read More', 'virtue') ;}
   $readmore = '>'.$readmore.'<';
      $excerpt = explode(' ', get_the_excerpt(), $limit);
      if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'...';
      } else {
        $excerpt = implode(" ",$excerpt);
      } 
      $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
      $excerpt = str_replace($readmore,'><',$excerpt);
      return $excerpt;
    }

function virtue_content($limit) {
      $content = explode(' ', get_the_content(), $limit);
      if (count($content)>=$limit) {
        array_pop($content);
        $content = implode(" ",$content).'...';
      } else {
        $content = implode(" ",$content);
      } 
      $content = preg_replace('/\[.+\]/','', $content);
      $content = apply_filters('the_content', $content); 
      $content = str_replace(']]>', ']]&gt;', $content);
      return $content;
    }
// Adjacent Post Plus Plugin

function get_adjacent_post_plus($r, $previous = true ) {
  global $post, $wpdb;

  extract( $r, EXTR_SKIP );

  if ( empty( $post ) )
    return null;

//  Sanitize $order_by, since we are going to use it in the SQL query. Default to 'post_date'.
  if ( in_array($order_by, array('post_date', 'post_title', 'post_excerpt', 'post_name', 'post_modified')) ) {
    $order_format = '%s';
  } elseif ( in_array($order_by, array('ID', 'post_author', 'post_parent', 'menu_order', 'comment_count')) ) {
    $order_format = '%d';
  } elseif ( $order_by == 'custom' && !empty($meta_key) ) { // Don't allow a custom sort if meta_key is empty.
    $order_format = '%s';
  } elseif ( $order_by == 'numeric' && !empty($meta_key) ) {
    $order_format = '%d';
  } else {
    $order_by = 'post_date';
    $order_format = '%s';
  }
  
//  Sanitize $order_2nd. Only columns containing unique values are allowed here. Default to 'post_date'.
  if ( in_array($order_2nd, array('post_date', 'post_title', 'post_modified')) ) {
    $order_format2 = '%s';
  } elseif ( in_array($order_2nd, array('ID')) ) {
    $order_format2 = '%d';
  } else {
    $order_2nd = 'post_date';
    $order_format2 = '%s';
  }
  
//  Sanitize num_results (non-integer or negative values trigger SQL errors)
  $num_results = intval($num_results) < 2 ? 1 : intval($num_results);

//  Queries involving custom fields require an extra table join
  if ( $order_by == 'custom' || $order_by == 'numeric' ) {
    $current_post = get_post_meta($post->ID, $meta_key, TRUE);
    $order_by = ($order_by === 'numeric') ? 'm.meta_value+0' : 'm.meta_value';
    $meta_join = $wpdb->prepare(" INNER JOIN $wpdb->postmeta AS m ON p.ID = m.post_id AND m.meta_key = %s", $meta_key );
  } elseif ( $in_same_meta ) {
    $current_post = $post->$order_by;
    $order_by = 'p.' . $order_by;
    $meta_join = $wpdb->prepare(" INNER JOIN $wpdb->postmeta AS m ON p.ID = m.post_id AND m.meta_key = %s", $in_same_meta );
  } else {
    $current_post = $post->$order_by;
    $order_by = 'p.' . $order_by;
    $meta_join = '';
  }

//  Get the current post value for the second sort column
  $current_post2 = $post->$order_2nd;
  $order_2nd = 'p.' . $order_2nd;
  
//  Get the list of post types. Default to current post type
  if ( empty($post_type) )
    $post_type = "'$post->post_type'";

//  Put this section in a do-while loop to enable the loop-to-first-post option
  do {
    $join = $meta_join;
    $excluded_categories = $ex_cats;
    $included_categories = $in_cats;
    $excluded_posts = $ex_posts;
    $included_posts = $in_posts;
    $in_same_term_sql = $in_same_author_sql = $in_same_meta_sql = $ex_cats_sql = $in_cats_sql = $ex_posts_sql = $in_posts_sql = '';

//    Get the list of hierarchical taxonomies, including customs (don't assume taxonomy = 'category')
    $taxonomies = array_filter( get_post_taxonomies($post->ID), "is_taxonomy_hierarchical" );

    if ( ($in_same_cat || $in_same_tax || $in_same_format || !empty($excluded_categories) || !empty($included_categories)) && !empty($taxonomies) ) {
      $cat_array = $tax_array = $format_array = array();

      if ( $in_same_cat ) {
        $cat_array = wp_get_object_terms($post->ID, $taxonomies, array('fields' => 'ids'));
      }
      if ( $in_same_tax && !$in_same_cat ) {
        if ( $in_same_tax === true ) {
          if ( $taxonomies != array('category') )
            $taxonomies = array_diff($taxonomies, array('category'));
        } else
          $taxonomies = (array) $in_same_tax;
        $tax_array = wp_get_object_terms($post->ID, $taxonomies, array('fields' => 'ids'));
      }
      if ( $in_same_format ) {
        $taxonomies[] = 'post_format';
        $format_array = wp_get_object_terms($post->ID, 'post_format', array('fields' => 'ids'));
      }

      $join .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy IN (\"" . implode('", "', $taxonomies) . "\")";

      $term_array = array_unique( array_merge( $cat_array, $tax_array, $format_array ) );
      if ( !empty($term_array) )
        $in_same_term_sql = "AND tt.term_id IN (" . implode(',', $term_array) . ")";

      if ( !empty($excluded_categories) ) {
//        Support for both (1 and 5 and 15) and (1, 5, 15) delimiter styles
        $delimiter = ( strpos($excluded_categories, ',') !== false ) ? ',' : 'and';
        $excluded_categories = array_map( 'intval', explode($delimiter, $excluded_categories) );
//        Three category exclusion methods are supported: 'strong', 'diff', and 'weak'.
//        Default is 'weak'. See the plugin documentation for more information.
        if ( $ex_cats_method === 'strong' ) {
          $taxonomies = array_filter( get_post_taxonomies($post->ID), "is_taxonomy_hierarchical" );
          if ( function_exists('get_post_format') )
            $taxonomies[] = 'post_format';
          $ex_cats_posts = get_objects_in_term( $excluded_categories, $taxonomies );
          if ( !empty($ex_cats_posts) )
            $ex_cats_sql = "AND p.ID NOT IN (" . implode($ex_cats_posts, ',') . ")";
        } else {
          if ( !empty($term_array) && !in_array($ex_cats_method, array('diff', 'differential')) )
            $excluded_categories = array_diff($excluded_categories, $term_array);
          if ( !empty($excluded_categories) )
            $ex_cats_sql = "AND tt.term_id NOT IN (" . implode($excluded_categories, ',') . ')';
        }
      }

      if ( !empty($included_categories) ) {
        $in_same_term_sql = ''; // in_cats overrides in_same_cat
        $delimiter = ( strpos($included_categories, ',') !== false ) ? ',' : 'and';
        $included_categories = array_map( 'intval', explode($delimiter, $included_categories) );
        $in_cats_sql = "AND tt.term_id IN (" . implode(',', $included_categories) . ")";
      }
    }

//    Optionally restrict next/previous links to same author    
    if ( $in_same_author )
      $in_same_author_sql = $wpdb->prepare("AND p.post_author = %d", $post->post_author );

//    Optionally restrict next/previous links to same meta value
    if ( $in_same_meta && $r['order_by'] != 'custom' && $r['order_by'] != 'numeric' )
      $in_same_meta_sql = $wpdb->prepare("AND m.meta_value = %s", get_post_meta($post->ID, $in_same_meta, TRUE) );

//    Optionally exclude individual post IDs
    if ( !empty($excluded_posts) ) {
      $excluded_posts = array_map( 'intval', explode(',', $excluded_posts) );
      $ex_posts_sql = " AND p.ID NOT IN (" . implode(',', $excluded_posts) . ")";
    }
    
//    Optionally include individual post IDs
    if ( !empty($included_posts) ) {
      $included_posts = array_map( 'intval', explode(',', $included_posts) );
      $in_posts_sql = " AND p.ID IN (" . implode(',', $included_posts) . ")";
    }

    $adjacent = $previous ? 'previous' : 'next';
    $order = $previous ? 'DESC' : 'ASC';
    $op = $previous ? '<' : '>';

//    Optionally get the first/last post. Disable looping and return only one result.
    if ( $end_post ) {
      $order = $previous ? 'ASC' : 'DESC';
      $num_results = 1;
      $loop = false;
      if ( $end_post === 'fixed' ) // display the end post link even when it is the current post
        $op = $previous ? '<=' : '>=';
    }

//    If there is no next/previous post, loop back around to the first/last post.   
    if ( $loop && isset($result) ) {
      $op = $previous ? '>=' : '<=';
      $loop = false; // prevent an infinite loop if no first/last post is found
    }
    
    $join  = apply_filters( "get_{$adjacent}_post_plus_join", $join, $r );

//    In case the value in the $order_by column is not unique, select posts based on the $order_2nd column as well.
//    This prevents posts from being skipped when they have, for example, the same menu_order.
    $where = apply_filters( "get_{$adjacent}_post_plus_where", $wpdb->prepare("WHERE ( $order_by $op $order_format OR $order_2nd $op $order_format2 AND $order_by = $order_format ) AND p.post_type IN ($post_type) AND p.post_status = 'publish' $in_same_term_sql $in_same_author_sql $in_same_meta_sql $ex_cats_sql $in_cats_sql $ex_posts_sql $in_posts_sql", $current_post, $current_post2, $current_post), $r );

    $sort  = apply_filters( "get_{$adjacent}_post_plus_sort", "ORDER BY $order_by $order, $order_2nd $order LIMIT $num_results", $r );

    $query = "SELECT DISTINCT p.* FROM $wpdb->posts AS p $join $where $sort";
    $query_key = 'adjacent_post_' . md5($query);
    $result = wp_cache_get($query_key);
    if ( false !== $result )
      return $result;

//    echo $query . '<br />';

//    Use get_results instead of get_row, in order to retrieve multiple adjacent posts (when $num_results > 1)
//    Add DISTINCT keyword to prevent posts in multiple categories from appearing more than once
    $result = $wpdb->get_results("SELECT DISTINCT p.* FROM $wpdb->posts AS p $join $where $sort");
    if ( null === $result )
      $result = '';

  } while ( !$result && $loop );

  wp_cache_set($query_key, $result);
  return $result;
}

/**
 * Display previous post link that is adjacent to the current post.
 *
 * Based on previous_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @return bool True if previous post link is found, otherwise false.
 */
function previous_post_link_plus($args = '') {
  return adjacent_post_link_plus($args, '&laquo; %link', true);
}

/**
 * Display next post link that is adjacent to the current post.
 *
 * Based on next_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @return bool True if next post link is found, otherwise false.
 */
function next_post_link_plus($args = '') {
  return adjacent_post_link_plus($args, '%link &raquo;', false);
}

/**
 * Display adjacent post link.
 *
 * Can be either next post link or previous.
 *
 * Based on adjacent_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @param bool $previous Optional, default is true. Whether display link to previous post.
 * @return bool True if next/previous post is found, otherwise false.
 */
function adjacent_post_link_plus($args = '', $format = '%link &raquo;', $previous = true) {
  $defaults = array(
    'order_by' => 'post_date', 'order_2nd' => 'post_date', 'meta_key' => '', 'post_type' => '',
    'loop' => false, 'end_post' => false, 'thumb' => false, 'max_length' => 0,
    'format' => '', 'link' => '%title', 'date_format' => '', 'tooltip' => '%title',
    'in_same_cat' => false, 'in_same_tax' => false, 'in_same_format' => false,
    'in_same_author' => false, 'in_same_meta' => false,
    'ex_cats' => '', 'ex_cats_method' => 'weak', 'in_cats' => '', 'ex_posts' => '', 'in_posts' => '',
    'before' => '', 'after' => '', 'num_results' => 1, 'return' => false, 'echo' => true
  );

//  If Post Types Order plugin is installed, default to sorting on menu_order
  if ( function_exists('CPTOrderPosts') )
    $defaults['order_by'] = 'menu_order';
  
  $r = wp_parse_args( $args, $defaults );
  if ( empty($r['format']) )
    $r['format'] = $format;
  if ( empty($r['date_format']) )
    $r['date_format'] = get_option('date_format');
  if ( !function_exists('get_post_format') )
    $r['in_same_format'] = false;

  if ( $previous && is_attachment() ) {
    $posts = array();
    $posts[] = & get_post($GLOBALS['post']->post_parent);
  } else
    $posts = get_adjacent_post_plus($r, $previous);

//  If there is no next/previous post, return false so themes may conditionally display inactive link text.
  if ( !$posts )
    return false;

//  If sorting by date, display posts in reverse chronological order. Otherwise display in alpha/numeric order.
  if ( ($previous && $r['order_by'] != 'post_date') || (!$previous && $r['order_by'] == 'post_date') )
    $posts = array_reverse( $posts, true );
    
//  Option to return something other than the formatted link    
  if ( $r['return'] ) {
    if ( $r['num_results'] == 1 ) {
      reset($posts);
      $post = current($posts);
      if ( $r['return'] === 'id')
        return $post->ID;
      if ( $r['return'] === 'href')
        return get_permalink($post);
      if ( $r['return'] === 'object')
        return $post;
      if ( $r['return'] === 'title')
        return $post->post_title;
      if ( $r['return'] === 'date')
        return mysql2date($r['date_format'], $post->post_date);
    } elseif ( $r['return'] === 'object')
      return $posts;
  }

  $output = $r['before'];

//  When num_results > 1, multiple adjacent posts may be returned. Use foreach to display each adjacent post.
  foreach ( $posts as $post ) {
    $title = $post->post_title;
    if ( empty($post->post_title) )
      $title = $previous ? __('Previous Post', 'virtue') : __('Next Post', 'virtue');

    $title = apply_filters('the_title', $title, $post->ID);
    $date = mysql2date($r['date_format'], $post->post_date);
    $author = get_the_author_meta('display_name', $post->post_author);
  
//    Set anchor title attribute to long post title or custom tooltip text. Supports variable replacement in custom tooltip.
    if ( $r['tooltip'] ) {
      $tooltip = str_replace('%title', $title, $r['tooltip']);
      $tooltip = str_replace('%date', $date, $tooltip);
      $tooltip = str_replace('%author', $author, $tooltip);
      $tooltip = ' title="' . esc_attr($tooltip) . '"';
    } else
      $tooltip = '';

//    Truncate the link title to nearest whole word under the length specified.
    $max_length = intval($r['max_length']) < 1 ? 9999 : intval($r['max_length']);
    if ( strlen($title) > $max_length )
      $title = substr( $title, 0, strrpos(substr($title, 0, $max_length), ' ') ) . '...';
  
    $rel = $previous ? 'prev' : 'next';

    $anchor = '<a href="'.get_permalink($post).'" rel="'.$rel.'"'.$tooltip.'>';
    $link = str_replace('%title', $title, $r['link']);
    $link = str_replace('%date', $date, $link);
    $link = $anchor . $link . '</a>';
  
    $format = str_replace('%link', $link, $r['format']);
    $format = str_replace('%title', $title, $format);
    $format = str_replace('%date', $date, $format);
    $format = str_replace('%author', $author, $format);
    if ( ($r['order_by'] == 'custom' || $r['order_by'] == 'numeric') && !empty($r['meta_key']) ) {
      $meta = get_post_meta($post->ID, $r['meta_key'], true);
      $format = str_replace('%meta', $meta, $format);
    } elseif ( $r['in_same_meta'] ) {
      $meta = get_post_meta($post->ID, $r['in_same_meta'], true);
      $format = str_replace('%meta', $meta, $format);
    }

//    Get the category list, including custom taxonomies (only if the %category variable has been used).
    if ( (strpos($format, '%category') !== false) && version_compare(PHP_VERSION, '5.0.0', '>=') ) {
      $term_list = '';
      $taxonomies = array_filter( get_post_taxonomies($post->ID), "is_taxonomy_hierarchical" );
      if ( $r['in_same_format'] && get_post_format($post->ID) )
        $taxonomies[] = 'post_format';
      foreach ( $taxonomies as &$taxonomy ) {
//        No, this is not a mistake. Yes, we are testing the result of the assignment ( = ).
//        We are doing it this way to stop it from appending a comma when there is no next term.
        if ( $next_term = get_the_term_list($post->ID, $taxonomy, '', ', ', '') ) {
          $term_list .= $next_term;
          if ( current($taxonomies) ) $term_list .= ', ';
        }
      }
      $format = str_replace('%category', $term_list, $format);
    }

//    Optionally add the post thumbnail to the link. Wrap the link in a span to aid CSS styling.
    if ( $r['thumb'] && has_post_thumbnail($post->ID) ) {
      if ( $r['thumb'] === true ) // use 'post-thumbnail' as the default size
        $r['thumb'] = 'post-thumbnail';
      $thumbnail = '<a class="post-thumbnail" href="'.get_permalink($post).'" rel="'.$rel.'"'.$tooltip.'>' . get_the_post_thumbnail( $post->ID, $r['thumb'] ) . '</a>';
      $format = $thumbnail . '<span class="post-link">' . $format . '</span>';
    }

//    If more than one link is returned, wrap them in <li> tags   
    if ( intval($r['num_results']) > 1 )
      $format = '<li>' . $format . '</li>';
    
    $output .= $format;
  }

  $output .= $r['after'];

  //  If echo is false, don't display anything. Return the link as a PHP string.
  if ( !$r['echo'] || $r['return'] === 'output' )
    return $output;

  $adjacent = $previous ? 'previous' : 'next';
  echo apply_filters( "{$adjacent}_post_link_plus", $output, $r );

  return true;
}
if (class_exists('SitePress')) {
global $sitepress;
add_filter('get_previous_post_plus_join', array($sitepress,'get_adjacent_post_join'));
add_filter('get_next_post_plus_join', array($sitepress,'get_adjacent_post_join'));
add_filter('get_previous_post_plus_where', array($sitepress,'get_adjacent_post_where'));
add_filter('get_next_post_plus_where', array($sitepress,'get_adjacent_post_where'));
}


//User Addon
add_action( 'show_user_profile', 'kad_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'kad_show_extra_profile_fields' );

function kad_show_extra_profile_fields( $user ) { ?>

<h3>Extra profile information</h3>

<table class="form-table">
  <tr>
    <th><label for="twitter"><?php _e('Occupation', 'virtue');?></label></th>
    <td>
      <input type="text" name="occupation" id="occupation" value="<?php echo esc_attr( get_the_author_meta( 'occupation', $user->ID ) ); ?>" class="regular-text" /><br />
      <span class="description"><?php _e('Please enter your Occupation.', 'virtue');?></span>
    </td>
  </tr>
  <tr>
    <th><label for="twitter">Twitter</label></th>
    <td>
      <input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
      <span class="description"><?php _e('Please enter your Twitter username.', 'virtue'); ?></span>
    </td>
  </tr>
    <tr>
    <th><label for="facebook">Facebook</label></th>
    <td>
      <input type="text" name="facebook" id="facebook" value="<?php echo esc_attr( get_the_author_meta( 'facebook', $user->ID ) ); ?>" class="regular-text" /><br />
      <span class="description"><?php _e('Please enter your Facebook url. (be sure to include http://)', 'virtue'); ?></span>
    </td>
  </tr>
    <tr>
    <th><label for="google">Google Plus</label></th>
    <td>
      <input type="text" name="google" id="google" value="<?php echo esc_attr( get_the_author_meta( 'google', $user->ID ) ); ?>" class="regular-text" /><br />
      <span class="description"><?php _e('Please enter your Google Plus url. (be sure to include http://)', 'virtue'); ?></span>
    </td>
  </tr>
   <tr>
    <th><label for="youtube">YouTube</label></th>
    <td>
      <input type="text" name="youtube" id="youtube" value="<?php echo esc_attr( get_the_author_meta( 'youtube', $user->ID ) ); ?>" class="regular-text" /><br />
      <span class="description"><?php _e('Please enter your YouTube url. (be sure to include http://)', 'virtue'); ?></span>
    </td>
  </tr>
    <tr>
    <th><label for="flickr">Flickr</label></th>
    <td>
      <input type="text" name="flickr" id="flickr" value="<?php echo esc_attr( get_the_author_meta( 'flickr', $user->ID ) ); ?>" class="regular-text" /><br />
      <span class="description"><?php _e('Please enter your Flickr url. (be sure to include http://)', 'virtue'); ?></span>
    </td>
  </tr>
    <tr>
    <th><label for="vimeo">Vimeo</label></th>
    <td>
      <input type="text" name="vimeo" id="vimeo" value="<?php echo esc_attr( get_the_author_meta( 'vimeo', $user->ID ) ); ?>" class="regular-text" /><br />
      <span class="description"><?php _e('Please enter your Vimeo url. (be sure to include http://)', 'virtue'); ?></span>
    </td>
  </tr>
    <tr>
    <th><label for="linkedin">Linkedin</label></th>
    <td>
      <input type="text" name="linkedin" id="linkedin" value="<?php echo esc_attr( get_the_author_meta( 'linkedin', $user->ID ) ); ?>" class="regular-text" /><br />
      <span class="description"><?php _e('Please enter your Linkedin url. (be sure to include http://)', 'virtue'); ?></span>
    </td>
  </tr>
    <tr>
    <th><label for="dribbble">Dribbble</label></th>
    <td>
      <input type="text" name="dribbble" id="dribbble" value="<?php echo esc_attr( get_the_author_meta( 'dribbble', $user->ID ) ); ?>" class="regular-text" /><br />
      <span class="description"><?php _e('Please enter your Dribbble url. (be sure to include http://)', 'virtue'); ?></span>
    </td>
  </tr>
    <tr>
    <th><label for="pinterest">Pinterest</label></th>
    <td>
      <input type="text" name="pinterest" id="pinterest" value="<?php echo esc_attr( get_the_author_meta( 'pinterest', $user->ID ) ); ?>" class="regular-text" /><br />
      <span class="description"><?php _e('Please enter your Pinterest url. (be sure to include http://)', 'virtue'); ?></span>
    </td>
  </tr>
  <tr>
    <th><label for="instagram">Instagram</label></th>
    <td>
      <input type="text" name="instagram" id="instagram" value="<?php echo esc_attr( get_the_author_meta( 'instagram', $user->ID ) ); ?>" class="regular-text" /><br />
      <span class="description"><?php _e('Please enter your Instagram url. (be sure to include http://)', 'virtue'); ?></span>
    </td>
  </tr>
</table>
<?php }
add_action( 'personal_options_update', 'kad_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'kad_save_extra_profile_fields' );

function kad_save_extra_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
  update_user_meta( $user_id, 'occupation', $_POST['occupation'] );
    update_user_meta( $user_id, 'twitter', $_POST['twitter'] );
  update_user_meta( $user_id, 'facebook', $_POST['facebook'] );
  update_user_meta( $user_id, 'google', $_POST['google'] );
  update_user_meta( $user_id, 'youtube', $_POST['youtube'] );
  update_user_meta( $user_id, 'flickr', $_POST['flickr'] );
  update_user_meta( $user_id, 'vimeo', $_POST['vimeo'] );
  update_user_meta( $user_id, 'linkedin', $_POST['linkedin'] );
  update_user_meta( $user_id, 'dribbble', $_POST['dribbble'] );
  update_user_meta( $user_id, 'pinterest', $_POST['pinterest'] );
  update_user_meta( $user_id, 'instagram', $_POST['instagram'] );
}

