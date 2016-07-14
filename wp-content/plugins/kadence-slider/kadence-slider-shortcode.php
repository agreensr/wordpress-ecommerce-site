<?php

function kad_slider_shortcode_function( $atts ) {
	extract(shortcode_atts(array(
		'id' => ''
), $atts));
	if(empty($id)) {
		return '<p class="error">' . __( 'Please specify a slider ID', 'kadence_slider' ) . '</p>';
	}
		$slides = array();
		$max_height = get_post_meta( $id, '_kt_slider_max_height', true );
		$max_width = get_post_meta( $id, '_kt_slider_max_width', true );
		$fullwidth = get_post_meta( $id, '_kt_slider_fullwidth', true );
		$respect_ratio = get_post_meta( $id, '_kt_slider_respect_ratio', true );
		$fullheight = get_post_meta( $id, '_kt_slider_fullheight', true );
		$pause_time = get_post_meta( $id, '_kt_slider_pause_time', true );
		$auto_play = get_post_meta( $id, '_kt_slider_auto_play', true );
		$btn_txt_color = get_post_meta( $id, '_kt_slider_btn_txt_color', true );
		$btn_bg_color = get_post_meta( $id, '_kt_slider_btn_bg_color', true );
		$btn_bord_color = get_post_meta( $id, '_kt_slider_btn_bord_color', true );
		$btn_txt_color_2 = get_post_meta( $id, '_kt_slider_btn_txt_color_2', true );
		$btn_bg_color_2 = get_post_meta( $id, '_kt_slider_btn_bg_color_2', true );
		$btn_back_color_2 = get_post_meta( $id, '_kt_slider_btn_back_color_2', true );
		$btn_txt_color_h = get_post_meta( $id, '_kt_slider_btn_txt_color_h', true );
		$btn_bg_color_h = get_post_meta( $id, '_kt_slider_btn_bg_color_h', true );
		$btn_bord_color_h = get_post_meta( $id, '_kt_slider_btn_bord_color_h', true );
		$btn_txt_color_2_h = get_post_meta( $id, '_kt_slider_btn_txt_color_2_h', true );
		$btn_bg_color_2_h = get_post_meta( $id, '_kt_slider_btn_bg_color_2_h', true );
		$btn_back_color_2_h = get_post_meta( $id, '_kt_slider_btn_back_color_2_h', true );
		$caption_head_font = get_post_meta( $id, '_kt_slider_head_font', true );
		$caption_head_font_weight = get_post_meta( $id, '_kt_slider_head_font_weight', true );
		$caption_head_font_spacing = get_post_meta( $id, '_kt_slider_head_font_spacing', true );
		$caption_header_txt_color = get_post_meta( $id, '_kt_slider_header_txt_color', true );
		$caption_text_font = get_post_meta( $id, '_kt_slider_text_font', true );
		$caption_text_font_weight = get_post_meta( $id, '_kt_slider_caption_text_font_weight', true );
		$caption_text_font_spacing = get_post_meta( $id, '_kt_slider_caption_text_font_spacing', true );
		$caption_caption_txt_color = get_post_meta( $id, '_kt_slider_caption_txt_color', true );
		$caption_caption_bg_color = get_post_meta( $id, '_kt_slider_caption_bg_color', true );
		$caption_caption_align = get_post_meta( $id, '_kt_slider_caption_align', true );
		$caption_caption_shaddow = get_post_meta( $id, '_kt_slider_caption_shaddow', true );
		$caption_caption_header_style = get_post_meta( $id, '_kt_slider_caption_header_style', true );
		$caption_head_font_tb = get_post_meta( $id, '_kt_slider_head_font_tb', true );
		$caption_text_font_tb = get_post_meta( $id, '_kt_slider_text_font_tb', true );
		$caption_head_font_pn = get_post_meta( $id, '_kt_slider_head_font_pn', true );
		$caption_text_font_pn = get_post_meta( $id, '_kt_slider_text_font_pn', true );
		$tabletmax_height = get_post_meta( $id, '_kt_slider_max_height_tablet', true );
		$mobilemax_height = get_post_meta( $id, '_kt_slider_max_height_mobile', true );

		$slider_parallax = get_post_meta( $id, '_kt_slider_parallax', true );
		$hidecontrols = get_post_meta( $id, '_kt_slider_hidecontrols', true );
		$data_height = $max_height;

		$slides = get_post_meta( $id, '_kt_slider_slides', true );
		$slidecount = count($slides);
		if(!empty($pause_time)) {$pause_data = $pause_time;} else{$pause_data = '9000';} 
		if(!empty($auto_play)) {$auto_play_data = $auto_play;} else{$auto_play_data = 'true';} 
		if($fullwidth) {$max_width = 'none';} else {$max_width = $max_width.'px';}
		if($fullheight) {$max_height = '600px'; $data_height_type = "full"; } else {$max_height = $max_height.'px'; $data_height_type = "normal";}

		if($slider_parallax) {$slider_parallax_class = 'kad-slider-parallax';} else {$slider_parallax_class = '';}
		$custom_slider_css = '';
		if($hidecontrols) {$custom_slider_css = '.kad-slider-navigate, .kad-slider ul.kad-slider-pagination {display:none !important;}';}
		if(!empty($btn_txt_color)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kad-slider-btn-01 {color:'.$btn_txt_color.';}'; }
		if(!empty($btn_bg_color)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kad-slider-btn-01 {background:'.$btn_bg_color.';}'; }
		if(!empty($btn_bord_color)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kad-slider-btn-01 {border-color:'.$btn_bord_color.';}'; }
		if(!empty($btn_txt_color_2)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kad-slider-btn-02 {color:'.$btn_txt_color_2.';}'; }
		if(!empty($btn_bg_color_2)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kad-slider-btn-02 {border-color:'.$btn_bg_color_2.';}'; }
		if(!empty($btn_back_color_2)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kad-slider-btn-02 {background:'.$btn_back_color_2.';}'; }
		if(!empty($btn_txt_color_h)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kad-slider-btn-01:hover {color:'.$btn_txt_color_h.';}'; }
		if(!empty($btn_bg_color_h)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kad-slider-btn-01:hover {background:'.$btn_bg_color_h.';}'; }
		if(!empty($btn_bord_color_h)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kad-slider-btn-01:hover {border-color:'.$btn_bord_color_h.';}'; }
		if(!empty($btn_txt_color_2_h)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kad-slider-btn-02:hover {color:'.$btn_txt_color_2_h.';}'; }
		if(!empty($btn_bg_color_2_h)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kad-slider-btn-02:hover {border-color:'.$btn_bg_color_2_h.';}'; }
		if(!empty($btn_back_color_2_h)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kad-slider-btn-02:hover {background:'.$btn_back_color_2_h.';}'; }
		if(!empty($caption_head_font)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontitle {font-size:'.$caption_head_font.'px;}'; }
		if(!empty($caption_head_font_weight)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontitle {font-weight:'.$caption_head_font_weight.';}'; }
		if(!empty($caption_head_font_spacing)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontitle {letter-spacing:'.$caption_head_font_spacing.';}'; }
		if(!empty($caption_header_txt_color)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontitle {color:'.$caption_header_txt_color.'; border-color: '.$caption_header_txt_color.';}'; }
		if(!empty($caption_text_font)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontext p {font-size:'.$caption_text_font.'px;}'; }
		if(!empty($caption_text_font_weight)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontext p {font-weight:'.$caption_text_font_weight.';}'; }
		if(!empty($caption_text_font_spacing)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontext p {letter-spacing:'.$caption_text_font_spacing.';}'; }
		if(!empty($caption_caption_txt_color)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontext p {color:'.$caption_caption_txt_color.';}'; }
		if(!empty($caption_caption_align)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .caption-case-inner {text-align:'.$caption_caption_align.';}'; }
		if(!empty($caption_caption_shaddow) && $caption_caption_shaddow == 'false') { $textshaddowclass = 'kad-slider-no-textshaddow';} else {$textshaddowclass = ''; }
		if(!empty($caption_caption_header_style) && $caption_caption_header_style == 'border') { $headerstyleclass = 'kt-border-title-style';} else {$headerstyleclass = ''; }
		if(!empty($caption_caption_bg_color)) { $cc_bg_rgb = ktslidehex2rgb($caption_caption_bg_color);
			$custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontext p, .kad-slider-'.$id.' .kad-slider .kadcaptiontitle {background-color: rgba('.$cc_bg_rgb[0].', '.$cc_bg_rgb[1].', '.$cc_bg_rgb[2].', 0.3);}'; }


		/*
		// CASE SWITCH FOR SLIDER TYPE //
		*/
		if(isset($respect_ratio) && $respect_ratio == true) {

 		$custom_slider_css .= '@media (max-width: 992px) {';
 		if(!empty($caption_text_font_tb)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontext p {font-size:'.$caption_text_font_tb.'px;}'; }
 		if(!empty($caption_head_font_tb)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontitle {font-size:'.$caption_head_font_tb.'px;}'; }
 		
 		$custom_slider_css .= '} @media (max-width: 767px) {';
 		if(!empty($caption_head_font_pn)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontitle {font-size:'.$caption_head_font_pn.'px;}'; }
 		if(!empty($caption_text_font_pn)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontext p {font-size:'.$caption_text_font_pn.'px;}'; }
 		$custom_slider_css .= '}';

 		$data_height_type = 'ratio';

                	ob_start(); ?>
                	<style type="text/css">
                	<?php echo $custom_slider_css; ?>
                	</style>
			  	<div class="kad-slider-wrapper <?php if($slidecount == 1) {echo 'kt_slider_single_slide';}?> kad-slider-<?php echo esc_attr($id);?> kt-ratio-slider" data-ktslider-id="<?php echo esc_attr($id);?>" data-ktslider-auto-play="<?php echo esc_attr($auto_play_data);?>" data-ktslider-pause-time="<?php echo esc_attr($pause_data);?>" data-ktslider-count="<?php echo esc_attr($slidecount);?>" data-ktslider-height="<?php echo esc_attr($data_height);?>" data-ktslider-height-type="<?php echo esc_attr($data_height_type);?>" style="max-width:<?php echo esc_attr($max_width);?>; margin-left: auto; margin-right:auto;">
			    	<div id="kad-slider-<?php echo esc_attr($id);?>" class="kad-slider kad-loading" style="margin-left: auto; margin-right:auto;">
			        	<ul class="kad-slider-canvas seq-canvas">
			        	<?php $slidenumber = 1;
			        	if(!empty($slides)) {
			            	foreach ($slides as $slide) :

			            	$slide_image_id = kt_get_image_id_by_link($slide['image']);
			           		$slide_image = wp_get_attachment_image_src ($slide_image_id, 'full');
			            	 ?>
			                      <li> 
			                              <div class="kad-slide kad-slide-<?php echo esc_attr($slidenumber);?> <?php echo esc_attr($textshaddowclass).' '.esc_attr($headerstyleclass);?>">
			                              <img src="<?php echo esc_url($slide['image']);?>" width="<?php echo esc_attr($slide_image['1']);?>"  height="<?php echo esc_attr($slide_image['2']);?>" class="kt-ratio-img">
			                              	<div class="caption-case kad-placement-<?php echo $slide["caption_placement"];?>">
			                                	<div class="kad-slide-caption animated <?php echo $slide["caption_animation_in"].' '.$slide["caption_animation_out"];?>">
			                                		<div class="caption-case-inner" data-0="opacity: 1;" data-300="opacity: 0;">
					                                <?php if (!empty($slide['title'])) echo '<div class="kadcaptiontitle clearfix headerfont">'.$slide['title'].'</div><div class="clearfix"></div>'; ?>
					                                <?php if (!empty($slide['description'])) echo '<div><div class="kadcaptiontext headerfont"><p>'.$slide['description'].'</p></div></div>';?>
					                                <?php if(!empty($slide['button_link'])) echo '<a href="'.$slide['button_link'].'" class="kad-slider-btn headerfont kad-slider-btn-01">'.$slide['button_txt'].'</a>'; ?>
					                                <?php if(!empty($slide['button_link_2'])) echo '<a href="'.$slide['button_link_2'].'" class="kad-slider-btn headerfont kad-slider-btn-02">'.$slide['button_txt_2'].'</a>'; ?>
			                                		</div> 
			                                	</div> 
			                            	</div>
			                            </div>
			                      	</li> 
			                      	<?php $slidenumber ++; ?>
			            <?php endforeach; ?>
			        	</ul>
			        	<ul class="kad-slider-pagination">
			        		<?php foreach ($slides as $slide) : ?>
						    <li class="kad-slider-dot"></li>
						    <?php endforeach; ?>
						</ul>
			        	<a class="kad-slider-next kad-slider-navigate"></a>
			        	<a class="kad-slider-prev kad-slider-navigate"></a>
			        	<?php } ?>
			      </div> <!--kad-slides-->
			  </div> <!--kad-slider-->
            		
	<?php  $output = ob_get_contents();
		ob_end_clean();
	return $output;
	} else {

		$custom_slider_css .= '@media (max-width: 992px) {';
 		if(!empty($caption_text_font_tb)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontext p {font-size:'.$caption_text_font_tb.'px;}'; }
 		if(!empty($caption_head_font_tb)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontitle {font-size:'.$caption_head_font_tb.'px;}'; }
 		if(!empty($tabletmax_height)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kad-slider-canvas, .kad-slider-'.$id.' .kad-slider {height:'.$tabletmax_height.'px !important;}'; }
 		
 		$custom_slider_css .= '} @media (max-width: 767px) {';
 		if(!empty($caption_head_font_pn)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontitle {font-size:'.$caption_head_font_pn.'px;}'; }
 		if(!empty($caption_text_font_pn)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kadcaptiontext p {font-size:'.$caption_text_font_pn.'px;}'; }
 		if(!empty($mobilemax_height)) { $custom_slider_css .= '.kad-slider-'.$id.' .kad-slider .kad-slider-canvas, .kad-slider-'.$id.' .kad-slider {height:'.$mobilemax_height.'px !important;}'; }
 		$custom_slider_css .= '}';

		               	ob_start(); ?>
                	<style type="text/css">
                	<?php echo $custom_slider_css; ?>
                	</style>
			  	<div class="kad-slider-wrapper <?php echo esc_attr($slider_parallax_class);?> <?php if($slidecount == 1) {echo 'kt_slider_single_slide';}?> kad-slider-<?php echo esc_attr($id);?>" data-ktslider-id="<?php echo esc_attr($id);?>" data-ktslider-auto-play="<?php echo esc_attr($auto_play_data);?>" data-ktslider-pause-time="<?php echo esc_attr($pause_data);?>" data-ktslider-height="<?php echo esc_attr($data_height);?>" data-ktslider-height-type="<?php echo esc_attr($data_height_type);?>" style="max-width:<?php echo esc_attr($max_width);?>; margin-left: auto; margin-right:auto;">
			    	<div id="kad-slider-<?php echo esc_attr($id);?>" class="kad-slider kad-loading" style="margin-left: auto; margin-right:auto; height:<?php echo esc_attr($max_height);?>; ">
			        	<ul class="kad-slider-canvas seq-canvas" style="height:<?php echo esc_attr($max_height);?>;" >
			        	<?php $slidenumber = 1;
			        	if(!empty($slides)) {
			            	foreach ($slides as $slide) : ?>
			                      <li> 
			                              <div class="kad-slide kad-slide-<?php echo esc_attr($slidenumber);?> <?php echo esc_attr($textshaddowclass).' '.esc_attr($headerstyleclass);?>" style="background-image: url('<?php echo $slide['image'];?>'); background-size:cover; background-repeat: no-repeat;">
			                              	<div class="caption-case kad-placement-<?php echo $slide["caption_placement"];?>">
			                                	<div class="kad-slide-caption animated <?php echo $slide["caption_animation_in"].' '.$slide["caption_animation_out"];?>">
			                                		<div class="caption-case-inner" data-0="opacity: 1;" data-300="opacity: 0;">
					                                <?php if (!empty($slide['title'])) echo '<div class="kadcaptiontitle clearfix headerfont">'.$slide['title'].'</div><div class="clearfix"></div>'; ?>
					                                <?php if (!empty($slide['description'])) echo '<div><div class="kadcaptiontext headerfont"><p>'.$slide['description'].'</p></div></div>';?>
					                                <?php if(!empty($slide['button_link'])) echo '<a href="'.$slide['button_link'].'" class="kad-slider-btn headerfont kad-slider-btn-01">'.$slide['button_txt'].'</a>'; ?>
					                                <?php if(!empty($slide['button_link_2'])) echo '<a href="'.$slide['button_link_2'].'" class="kad-slider-btn headerfont kad-slider-btn-02">'.$slide['button_txt_2'].'</a>'; ?>
			                                		</div> 
			                                	</div> 
			                            	</div>
			                            </div>
			                      	</li> 
			                      	<?php $slidenumber ++; ?>
			            <?php endforeach; ?>
			        	</ul>
			        	<ul class="kad-slider-pagination">
			        		<?php foreach ($slides as $slide) : ?>
						    <li class="kad-slider-dot"></li>
						    <?php endforeach; ?>
						</ul>
			        	<a class="kad-slider-next kad-slider-navigate"></a>
			        	<a class="kad-slider-prev kad-slider-navigate"></a>
			        	<?php } ?>
			      </div> <!--kad-slides-->
			  </div> <!--kad-slider-->
            		
	<?php  $output = ob_get_contents();
		ob_end_clean();
	return $output;
	}
}

function kadence_slider_shortcode(){
   add_shortcode('kadence_slider', 'kad_slider_shortcode_function');
}
add_action( 'init', 'kadence_slider_shortcode');

