<?php

/*
Plugin Name: Kadence Slider
Description: Responsive background image slider with caption animations.
Version: 1.5.3
Author: Kadence Themes
Author URI: http://kadencethemes.com/
License: GPLv2 or later
*/
function kadence_slider_activation() {
}
register_activation_hook(__FILE__, 'kadence_slider_activation');

function kadence_slider_deactivation() {
}
register_deactivation_hook(__FILE__, 'kadence_slider_deactivation');

require_once('kadence-slider-init.php');
require_once('kadence-slider-shortcode.php');
require_once('kadence-slider-metabox.php');

if(!defined('KADENCE_SLIDER_PATH')){
	define('KADENCE_SLIDER_PATH', realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR );
}
if(!defined('KADENCE_SLIDER_URL')){
	define('KADENCE_SLIDER_URL', plugin_dir_url(__FILE__) );
}


function kadence_slider_scripts() {
  wp_enqueue_style('kadence_slider_css', KADENCE_SLIDER_URL . 'css/kad-slider.css', false, '153');
  wp_register_script('kadence_slider_js', KADENCE_SLIDER_URL . 'js/min/kadence-slider-min.js', false, 153, true);
  wp_enqueue_script('jquery');
  wp_enqueue_script('kadence_slider_js');

}
add_action('wp_enqueue_scripts', 'kadence_slider_scripts', 100);

function kad_slider_edit_page(){
  if (!is_admin()) return false;

    if ( in_array( $GLOBALS['pagenow'], array( 'post.php', 'post-new.php', 'widgets.php', 'post.php', 'post-new.php' ) ) ) {
      return true;
    }
}
function kadence_slider_admin_scripts() {
  wp_enqueue_style('kadence_slider_admin', KADENCE_SLIDER_URL . 'css/kad-slider-admin.css', false, '153');

}

function kadence_slider_script_init() {
	if(is_admin()){ if(kad_slider_edit_page()){add_action( 'admin_enqueue_scripts', 'kadence_slider_admin_scripts', 100);	}}
	
}
add_action('init', 'kadence_slider_script_init');

function ktslidehex2rgb($hex) {
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

function kt_get_image_id_by_link($link){
    global $wpdb;
    $link = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $link);

    return $wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE BINARY guid='$link'");
}
/* Filter the single_template with our custom function*/

require_once('wp-updates-plugin.php');

$kad_slider_updater = new PluginUpdateChecker_2_0 ('https://kernl.us/api/v1/updates/5679e8dd6f276b6452e41eb4/',__FILE__, 'kadence-slider', 1);


