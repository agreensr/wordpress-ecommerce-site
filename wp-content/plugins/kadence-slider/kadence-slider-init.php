<?php
// Custom post types
function kadslider_post_init() {
  $slidelabels = array(
    'name' =>  __('Slider', 'virtue'),
    'singular_name' => __('Slider Item', 'virtue'),
    'add_new' => __('Add New', 'virtue'),
    'add_new_item' => __('Add New Slider Item', 'virtue'),
    'edit_item' => __('Edit Slider Item', 'virtue'),
    'new_item' => __('New Slider Item', 'virtue'),
    'all_items' => __('All Sliders', 'virtue'),
    'view_item' => __('View Slider Item', 'virtue'),
    'search_items' => __('Search Slider', 'virtue'),
    'not_found' =>  __('No Slider Item found', 'virtue'),
    'not_found_in_trash' => __('No Slider Items found in Trash', 'virtue'),
    'parent_item_colon' => '',
    'menu_name' => __('Kadence Slider', 'virtue')
  );

  $sliderargs = array(
    'labels' => $slidelabels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite'  => array( 'slug' => 'kad-slider' ), /* you can specify its url slug */
    'has_archive' => false, 
    'capability_type' => 'post', 
    'hierarchical' => false,
    'menu_position' => 82,
    'menu_icon' =>  'dashicons-images-alt2',
    'supports' => array( 'title')
  ); 

  register_post_type( 'kadslider', $sliderargs );
}
add_action( 'init', 'kadslider_post_init', 10 );
	
