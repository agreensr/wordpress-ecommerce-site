<?php
add_action( 'cmb_render_slider_useage', 'kt_cmb_render_slider_useage', 10, 5 );
function kt_cmb_render_slider_useage( $field_args, $escaped_value, $object_id, $object_type, $field_type_object ) {
	global $post;
	echo '<code>';
	echo '[kadence_slider id="' .$post->ID. '"]';
	echo '</code>';
}
add_action( 'cmb_render_text_number', 'sm_cmb_render_text_number', 10, 5 );
function sm_cmb_render_text_number( $field_args, $escaped_value, $object_id, $object_type, $field_type_object ) {
    echo $field_type_object->input( array( 'class' => 'cmb_text_small', 'type' => 'number' ) );
}
// validate the field
add_filter( 'cmb_validate_text_number', 'sm_cmb_validate_text_number' );
function sm_cmb_validate_text_number( $new ) {
   $bnew = preg_replace("/[^0-9]/","",$new);

    return $new;
}

add_filter( 'cmb_meta_boxes', 'kadence_slider_metaboxes' );
function kadence_slider_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_kt_slider_';

	$meta_boxes[] = array(
				'id'         => 'slider_settings',
				'title'      => __('Basic Slider Settings', 'kadence_slider'),
				'pages'      => array( 'kadslider' ), // Post type
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
				array(
					'name' => __('Slider Shortcode', 'kadence_slider'),
					'desc' => '',
					'id'   => $prefix . 'slider_useage',
					'type' => 'slider_useage',
				),
				array(
					'name' => __('Slider Max Height', 'kadence_slider'),
					'desc' => __('This sets a max height for your slider', 'kadence_slider'),
					'id'   => $prefix . 'max_height',
					'type' => 'text_number',
					'default' => '450',
				),
				array(
					'name' => __('Slider Max Width', 'kadence_slider'),
					'desc' => __('This sets a max width for your slider', 'kadence_slider'),
					'id'   => $prefix . 'max_width',
					'type' => 'text_number',
					'default' => '1140',
				),
				array(
					'name' => __('Full Width', 'kadence_slider'),
					'desc' => __('This will ignore the max width setting.', 'kadence_slider'),
					'id' => $prefix . 'fullwidth',
					'type' => 'checkbox'
				),
				array(
					'name' => __('Full Height', 'kadence_slider'),
					'desc' => __('This will ignore the max height setting.', 'kadence_slider'),
					'id' => $prefix . 'fullheight',
					'type' => 'checkbox'
				),
				array(
					'name' => __('Respect Image Aspect Ratio', 'kadence_slider'),
					'desc' => __('This will ignore the full height and display the images based on the uploaded ratio once they fall below the max height. You can not use with fullheight or parallax', 'kadence_slider'),
					'id' => $prefix . 'respect_ratio',
					'type' => 'checkbox'
				),
				array(
					'name' => __('Auto Play', 'kadence_slider'),
					'desc' => __('Scroll through slides automatically.', 'kadence_slider'),
					'id'   => $prefix . 'auto_play',
					'type'    => 'select',
					'options' => array(
						array( 'name' => __( 'True', 'kadence_slider' ), 'value' => 'true', ),
						array( 'name' => __( 'False', 'kadence_slider' ), 'value' => 'false', ),
					),
		    	),
				array(
					'name' => __('Slide Pause Time', 'kadence_slider'),
					'desc' => __('This sets the time each slide is displayed in milliseconds.', 'kadence_slider'),
					'id'   => $prefix . 'pause_time',
					'type' => 'text_number',
					'default' => '9000',
				),
				array(
					'name' => __('Enable Parallax', 'kadence_slider'),
					'desc' => __('This fix the slider image from scrolling.', 'kadence_slider'),
					'id' => $prefix . 'parallax',
					'type' => 'checkbox'
				),
				array(
					'name' => __('Hide Controls?', 'kadence_slider'),
					'id' => $prefix . 'hidecontrols',
					'type' => 'checkbox'
				),
				
		),
);
$meta_boxes[] = array(
				'id'         => 'slider_slides',
				'title'      => __('Slider Slides', 'kadence_slider'),
				'pages'      => array( 'kadslider' ), // Post type
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
			array(
				    'id'          => $prefix . 'slides',
				    'type'        => 'group',
				    'options'     => array(
				        'group_title'   => __( 'Slide {#}', 'kadence_slider' ), // since version 1.1.4, {#} gets replaced by row number
				        'add_button'    => __( 'Add Another Slide', 'kadence_slider' ),
				        'remove_button' => __( 'Remove Slide', 'kadence_slider' ),
				        'sortable'      => true, // beta
				    ),
    		'fields'      => array(
			        array(
			            'name' => __('Slider Caption Heading', 'kadence_slider' ),
			            'id'   => 'title',
			            'type' => 'text',
			        ),
			        array(
			        	'name' => __('Slider Caption Description', 'kadence_slider' ),
			            'description' => __('A short description for this slide', 'kadence_slider' ),
			            'id'   => 'description',
			            'type' => 'textarea_small',
			        ),
			        array(
			            'name' => __('Slide Image', 'kadence_slider'),
			            'id'   => 'image',
			            'type' => 'file',
			        ),
			        array(
			            'name' => __('Primary Button Text', 'kadence_slider'),
			            'id'   => 'button_txt',
			            'type' => 'text',
			        ),
			        array(
			            'name' => __('Primary Button Link', 'kadence_slider'),
			            'id'   => 'button_link',
			            'type' => 'text',
			        ),
			        array(
			            'name' => __('Secondary Button Text', 'kadence_slider'),
			            'id'   => 'button_txt_2',
			            'type' => 'text',
			        ),
			        array(
			            'name' => __('Secondary Button Link', 'kadence_slider'),
			            'id'   => 'button_link_2',
			            'type' => 'text',
			        ),
			        array(
					'name' => __('Caption Placement', 'kadence_slider'),
					'desc' => __('This positions the caption in your slide area', 'kadence_slider'),
					'id'   => 'caption_placement',
					'type'    => 'select',
					'options' => array(
						array( 'name' => __( 'Center Center', 'kadence_slider' ), 'value' => 'cc', ),
						array( 'name' => __( 'Top Center', 'kadence_slider' ), 'value' => 'tc', ),
						array( 'name' => __( 'Bottom Center', 'kadence_slider' ), 'value' => 'bc', ),
						array( 'name' => __( 'Left Center', 'kadence_slider' ), 'value' => 'lc', ),
						array( 'name' => __( 'Right Center', 'kadence_slider' ), 'value' => 'rc', ),
						array( 'name' => __( 'Top Left', 'kadence_slider' ), 'value' => 'tl', ),
						array( 'name' => __( 'Top Right', 'kadence_slider' ), 'value' => 'tr', ),
						array( 'name' => __( 'Bottom Left', 'kadence_slider' ), 'value' => 'bl', ),
						array( 'name' => __( 'Bottom Right', 'kadence_slider' ), 'value' => 'br', ),
					),
		    	),
			         array(
					'name' => __('Caption In Animation', 'kadence_slider'),
					'desc' => __('This determines the caption animation as it starts the slide', 'kadence_slider'),
					'id'   => 'caption_animation_in',
					'type'    => 'select',
					'options' => array(
					array( 'name' => __( 'fadeIn', 'kadence_slider' ), 'value' => 'fadeIn', ),
		          	array( 'name' => __( 'fadeInDown', 'kadence_slider' ), 'value' => 'fadeInDown', ),
		          	array( 'name' => __( 'fadeInDownBig', 'kadence_slider' ), 'value' => 'fadeInDownBig', ),
		          	array( 'name' => __( 'fadeInLeft', 'kadence_slider' ), 'value' => 'fadeInLeft', ),
		          	array( 'name' => __( 'fadeInLeftBig', 'kadence_slider' ), 'value' => 'fadeInLeftBig', ),
		          	array( 'name' => __( 'fadeInRight', 'kadence_slider' ), 'value' => 'fadeInRight', ),
		           	array( 'name' => __( 'fadeInRightBig', 'kadence_slider' ), 'value' => 'fadeInRightBig', ),
		            array( 'name' => __( 'fadeInUp', 'kadence_slider' ), 'value' => 'fadeInUp', ),
		          	array( 'name' => __( 'fadeInUpBig', 'kadence_slider' ), 'value' => 'fadeInUpBig', ),
		          	array( 'name' => __( 'bounceIn', 'kadence_slider' ), 'value' => 'bounceIn', ),
			          array( 'name' => __( 'bounceInDown', 'kadence_slider' ), 'value' => 'bounceInDown', ),
			          array( 'name' => __( 'bounceInLeft', 'kadence_slider' ), 'value' => 'bounceInLeft', ),
			          array( 'name' => __( 'bounceInRight', 'kadence_slider' ), 'value' => 'bounceInRight', ),
			          array( 'name' => __( 'bounceInUp', 'kadence_slider' ), 'value' => 'bounceInUp', ),
			          array( 'name' => __( 'rotateIn', 'kadence_slider' ), 'value' => 'rotateIn', ),
			          array( 'name' => __( 'rotateInDownLeft', 'kadence_slider' ), 'value' => 'rotateInDownLeft', ),
			          array( 'name' => __( 'rotateInDownRight', 'kadence_slider' ), 'value' => 'rotateInDownRight', ),
			          array( 'name' => __( 'rotateInUpLeft', 'kadence_slider' ), 'value' => 'rotateInUpLeft', ),
			          array( 'name' => __( 'rotateInUpRight', 'kadence_slider' ), 'value' => 'rotateInUpRight', ),
			          array( 'name' => __( 'slideInDown', 'kadence_slider' ), 'value' => 'slideInDown', ),
			          array( 'name' => __( 'slideInLeft', 'kadence_slider' ), 'value' => 'slideInLeft', ),
			          array( 'name' => __( 'slideInRight', 'kadence_slider' ), 'value' => 'slideInRight', ),
			          array( 'name' => __( 'rollIn', 'kadence_slider' ), 'value' => 'rollIn', ),
			          ),
				),
				 array(
					'name' => __('Caption Out Animation', 'kadence_slider'),
					'desc' => __('This determines the caption animation as it switches to the next slide', 'kadence_slider'),
					'id'   => 'caption_animation_out',
					'type'    => 'select',
					'options' => array(
						 array( 'name' => __( 'fadeOut', 'kadence_slider' ), 'value' => 'fadeOut', ),
				         array( 'name' => __( 'fadeOutDown', 'kadence_slider' ), 'value' => 'fadeOutDown', ),
				         array( 'name' => __( 'fadeOutDownBig', 'kadence_slider' ), 'value' => 'fadeOutDownBig', ),
				         array( 'name' => __( 'fadeOutLeft', 'kadence_slider' ), 'value' => 'fadeOutLeft', ),
				         array( 'name' => __( 'fadeOutLeftBig', 'kadence_slider' ), 'value' => 'fadeOutLeftBig', ),
				         array( 'name' => __( 'fadeOutRight', 'kadence_slider' ), 'value' => 'fadeOutRight', ),
				         array( 'name' => __( 'fadeOutRightBig', 'kadence_slider' ), 'value' => 'fadeOutRightBig', ),
				         array( 'name' => __( 'fadeOutUp', 'kadence_slider' ), 'value' => 'fadeOutUp', ),
				         array( 'name' => __( 'fadeOutUpBig', 'kadence_slider' ), 'value' => 'fadeOutUpBig', ),
				          array( 'name' => __( 'bounceOut', 'kadence_slider' ), 'value' => 'bounceOut', ),
				          array( 'name' => __( 'bounceOutDown', 'kadence_slider' ), 'value' => 'bounceOutDown', ),
				          array( 'name' => __( 'bounceOutLeft', 'kadence_slider' ), 'value' => 'bounceOutLeft', ),
				          array( 'name' => __( 'bounceOutRight', 'kadence_slider' ), 'value' => 'bounceOutRight', ),
				          array( 'name' => __( 'bounceOutUp', 'kadence_slider' ), 'value' => 'bounceOutUp', ),
				          array( 'name' => __( 'rotateOut', 'kadence_slider' ), 'value' => 'rotateOut', ),
				          array( 'name' => __( 'rotateOutDownLeft', 'kadence_slider' ), 'value' => 'rotateOutDownLeft', ),
				          array( 'name' => __( 'rotateOutDownRight', 'kadence_slider' ), 'value' => 'rotateOutDownRight', ),
				          array( 'name' => __( 'rotateOutUpLeft', 'kadence_slider' ), 'value' => 'rotateOutUpLeft', ),
				          array( 'name' => __( 'rotateOutUpRight', 'kadence_slider' ), 'value' => 'rotateOutUpRight', ),
				          array( 'name' => __( 'slideOutLeft', 'kadence_slider' ), 'value' => 'slideOutLeft', ),
				          array( 'name' => __( 'slideOutRight', 'kadence_slider' ), 'value' => 'slideOutRight', ),
				          array( 'name' => __( 'slideOutUp', 'kadence_slider' ), 'value' => 'slideOutUp', ),
				          array( 'name' => __( 'rollOut', 'kadence_slider' ), 'value' => 'rollOut', ),
					),
				),
			),
),
),
);
$meta_boxes[] = array(
				'id'         => 'button_slider_settings',
				'title'      => __('Button Color Settings', 'kadence_slider'),
				'pages'      => array( 'kadslider' ), // Post type
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
				array(
					    'name' => __('Primary Button Text Color', 'kadence_slider'),
					    'id'   => $prefix . 'btn_txt_color',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				array(
					    'name' => __('Primary Button Background Color', 'kadence_slider'),
					    'id'   => $prefix . 'btn_bg_color',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				array(
					    'name' => __('Primary Button Border Color', 'kadence_slider'),
					    'id'   => $prefix . 'btn_bord_color',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				array(
					    'name' => __('Primary Button Hover Text Color', 'kadence_slider'),
					    'id'   => $prefix . 'btn_txt_color_h',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				array(
					    'name' => __('Primary Button Hover Background Color', 'kadence_slider'),
					    'id'   => $prefix . 'btn_bg_color_h',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				array(
					    'name' => __('Primary Button Hover Border Color', 'kadence_slider'),
					    'id'   => $prefix . 'btn_bord_color_h',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				array(
					    'name' => __('Secondary Button Text Color', 'kadence_slider'),
					    'id'   => $prefix . 'btn_txt_color_2',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				array(
					    'name' => __('Secondary Button Background Color', 'kadence_slider'),
					    'id'   => $prefix . 'btn_back_color_2',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				array(
					    'name' => __('Secondary Button Border Color', 'kadence_slider'),
					    'id'   => $prefix . 'btn_bg_color_2',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				array(
					    'name' => __('Secondary Button Hover Text Color', 'kadence_slider'),
					    'id'   => $prefix . 'btn_txt_color_2_h',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				array(
					    'name' => __('Secondary Button Hover Background Color', 'kadence_slider'),
					    'id'   => $prefix . 'btn_back_color_2_h',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				array(
					    'name' => __('Secondary Button Hover Border Color', 'kadence_slider'),
					    'id'   => $prefix . 'btn_bg_color_2_h',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				
		),
);
$meta_boxes[] = array(
				'id'         => 'caption_slider_settings',
				'title'      => __('Slider Caption Settings', 'kadence_slider'),
				'pages'      => array( 'kadslider' ), // Post type
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
				array(
					    'name' => __('Caption Header Text Color', 'kadence_slider'),
					    'id'   => $prefix . 'header_txt_color',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				array(
					'name' => __('Caption Header Text Font Size', 'kadence_slider'),
					'id'   => $prefix . 'head_font',
					'type' => 'text_number',
					'default' => '76',
				),
				array(
					'name' => __('Caption Header Text Font Weight', 'kadence_slider'),
					'id'   => $prefix . 'head_font_weight',
					'type'    => 'select',
					'default' => '400',
					'options' => array(
						array( 'name' => __( '100', 'kadence_slider' ), 'value' => '100', ),
						array( 'name' => __( '200', 'kadence_slider' ), 'value' => '200', ),
						array( 'name' => __( '300', 'kadence_slider' ), 'value' => '300', ),
						array( 'name' => __( '400', 'kadence_slider' ), 'value' => '400', ),
						array( 'name' => __( '500', 'kadence_slider' ), 'value' => '500', ),
						array( 'name' => __( '600', 'kadence_slider' ), 'value' => '600', ),
						array( 'name' => __( '700', 'kadence_slider' ), 'value' => '700', ),
						array( 'name' => __( '800', 'kadence_slider' ), 'value' => '800', ),
						array( 'name' => __( '900', 'kadence_slider' ), 'value' => '900', ),
					),
		    	),
		    	array(
					'name' => __('Caption Header Text Letter Spacing', 'kadence_slider'),
					'id'   => $prefix . 'head_font_spacing',
					'type'    => 'select',
					'default' => 'normal',
					'options' => array(
						array( 'name' => __( 'Normal', 'kadence_slider' ), 'value' => 'normal', ),
						array( 'name' => __( '1', 'kadence_slider' ), 'value' => '1px', ),
						array( 'name' => __( '2', 'kadence_slider' ), 'value' => '2px', ),
						array( 'name' => __( '3', 'kadence_slider' ), 'value' => '3px', ),
						array( 'name' => __( '4', 'kadence_slider' ), 'value' => '4px', ),
						array( 'name' => __( '5', 'kadence_slider' ), 'value' => '5px', ),
						array( 'name' => __( '6', 'kadence_slider' ), 'value' => '6px', ),
						array( 'name' => __( '7', 'kadence_slider' ), 'value' => '7px', ),
						array( 'name' => __( '8', 'kadence_slider' ), 'value' => '8px', ),
						array( 'name' => __( '9', 'kadence_slider' ), 'value' => '9px', ),
						array( 'name' => __( '10', 'kadence_slider' ), 'value' => '10px', ),
						array( 'name' => __( '11', 'kadence_slider' ), 'value' => '11px', ),
						array( 'name' => __( '12', 'kadence_slider' ), 'value' => '12px', ),
					),
		    	),
				array(
					'name' => __('Caption Header Text Style', 'kadence_slider'),
					'id'   => $prefix . 'caption_header_style',
					'type'    => 'select',
					'options' => array(
						array( 'name' => __( 'Normal', 'kadence_slider' ), 'value' => 'normal', ),
						array( 'name' => __( 'Top and Bottom Border', 'kadence_slider' ), 'value' => 'border', ),
					),
		    	),
				array(
					    'name' => __('Caption Description Text Color', 'kadence_slider'),
					    'id'   => $prefix . 'caption_txt_color',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				array(
					'name' => __('Caption Description Text Font Size', 'kadence_slider'),
					'id'   => $prefix . 'text_font',
					'type' => 'text_number',
					'default' => '24',
				),
				array(
					'name' => __('Caption Description Text Font Weight', 'kadence_slider'),
					'id'   => $prefix . 'caption_text_font_weight',
					'type'    => 'select',
					'default' => '400',
					'options' => array(
						array( 'name' => __( '100', 'kadence_slider' ), 'value' => '100', ),
						array( 'name' => __( '200', 'kadence_slider' ), 'value' => '200', ),
						array( 'name' => __( '300', 'kadence_slider' ), 'value' => '300', ),
						array( 'name' => __( '400', 'kadence_slider' ), 'value' => '400', ),
						array( 'name' => __( '500', 'kadence_slider' ), 'value' => '500', ),
						array( 'name' => __( '600', 'kadence_slider' ), 'value' => '600', ),
						array( 'name' => __( '700', 'kadence_slider' ), 'value' => '700', ),
						array( 'name' => __( '800', 'kadence_slider' ), 'value' => '800', ),
						array( 'name' => __( '900', 'kadence_slider' ), 'value' => '900', ),
					),
		    	),
		    	array(
					'name' => __('Caption Description Text Letter Spacing', 'kadence_slider'),
					'id'   => $prefix . 'caption_text_font_spacing',
					'type'    => 'select',
					'default' => 'normal',
					'options' => array(
						array( 'name' => __( 'Normal', 'kadence_slider' ), 'value' => 'normal', ),
						array( 'name' => __( '1', 'kadence_slider' ), 'value' => '1px', ),
						array( 'name' => __( '2', 'kadence_slider' ), 'value' => '2px', ),
						array( 'name' => __( '3', 'kadence_slider' ), 'value' => '3px', ),
						array( 'name' => __( '4', 'kadence_slider' ), 'value' => '4px', ),
						array( 'name' => __( '5', 'kadence_slider' ), 'value' => '5px', ),
						array( 'name' => __( '6', 'kadence_slider' ), 'value' => '6px', ),
						array( 'name' => __( '7', 'kadence_slider' ), 'value' => '7px', ),
						array( 'name' => __( '8', 'kadence_slider' ), 'value' => '8px', ),
						array( 'name' => __( '9', 'kadence_slider' ), 'value' => '9px', ),
						array( 'name' => __( '10', 'kadence_slider' ), 'value' => '10px', ),
						array( 'name' => __( '11', 'kadence_slider' ), 'value' => '11px', ),
						array( 'name' => __( '12', 'kadence_slider' ), 'value' => '12px', ),
					),
		    	),
				array(
					    'name' => __('Caption Background Color', 'kadence_slider'),
					    'id'   => $prefix . 'caption_bg_color',
					    'type' => 'colorpicker',
					    'default'  => '',
				),
				array(
					'name' => __('Caption Text Align', 'kadence_slider'),
					'desc' => __('This will align the caption text.', 'kadence_slider'),
					'id'   => $prefix . 'caption_align',
					'type'    => 'select',
					'options' => array(
						array( 'name' => __( 'Center', 'kadence_slider' ), 'value' => 'center', ),
						array( 'name' => __( 'Left', 'kadence_slider' ), 'value' => 'left', ),
						array( 'name' => __( 'Right', 'kadence_slider' ), 'value' => 'right', ),
					),
		    	),
		    	array(
					'name' => __('Caption Text Shaddow', 'kadence_slider'),
					'id'   => $prefix . 'caption_shaddow',
					'type'    => 'select',
					'options' => array(
						array( 'name' => __( 'Show Text Shaddow', 'kadence_slider' ), 'value' => 'true', ),
						array( 'name' => __( 'Hide Text Shaddow', 'kadence_slider' ), 'value' => 'false', ),
					),
		    	),
				
		),
);
$meta_boxes[] = array(
				'id'         => 'mobile_caption_slider_settings',
				'title'      => __('Mobile Slider Caption and Size Settings', 'kadence_slider'),
				'pages'      => array( 'kadslider' ), // Post type
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
				array(
					'name' => __('Tablet - Caption Header Text Font Size', 'kadence_slider'),
					'id'   => $prefix . 'head_font_tb',
					'type' => 'text_number',
					'default' => '50',
				),
				array(
					'name' => __('Tablet - Caption Description Text Font Size', 'kadence_slider'),
					'id'   => $prefix . 'text_font_tb',
					'type' => 'text_number',
					'default' => '18',
				),
				array(
					'name' => __('Phone - Caption Header Text Font Size', 'kadence_slider'),
					'id'   => $prefix . 'head_font_pn',
					'type' => 'text_number',
					'default' => '26',
				),
				array(
					'name' => __('Phone - Caption Description Text Font Size', 'kadence_slider'),
					'id'   => $prefix . 'text_font_pn',
					'type' => 'text_number',
					'default' => '14',
				),
				array(
					'name' => __('Tablet - Slider Max Height(overrides settings)', 'kadence_slider'),
					'desc' => __('This sets a max height for your slider for tablet', 'kadence_slider'),
					'id'   => $prefix . 'max_height_tablet',
					'type' => 'text_number',
					'default' => '',
				),
				array(
					'name' => __('Mobile - Slider Max Height(overrides settings)', 'kadence_slider'),
					'desc' => __('This sets a max height for your slider for mobile', 'kadence_slider'),
					'id'   => $prefix . 'max_height_mobile',
					'type' => 'text_number',
					'default' => '',
				),
		),
);
		return $meta_boxes;
}
add_action( 'init', 'initialize_slider_meta_boxes', 10 );
function initialize_slider_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'cmb/init.php';

}
