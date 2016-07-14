<?php 
//Shortcode for Blog Posts
function kad_image_menu_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'height' => '110',
		'height_setting' => 'normal',
		'image' => '',
		'title' => '',
		'columns' => '3',
		'link' => '',
		'target' => '',
		'description' => ''
), $atts));
	if(!empty($target)) {$linktarget = 'target="'.$target.'"';} else {$linktarget = '';}
	if(empty($image)) { $image = virtue_img_placeholder();}

    if ($columns == '2') {
    	$itemsize = 'tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $width = 559;
    } else if ($columns == '1'){
    	$itemsize = '';  $width = null;
    } else if ($columns == '3'){
    	$itemsize = 'tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';  $width = 367;
    } else if ($columns == '6'){
    	$itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $width = 240;
    } else if ($columns == '5'){
    	$itemsize = 'tcol-lg-25 tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $width = 240;
    } else {
    	$itemsize = 'tcol-lg-3 tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $width = 270;
	}             
   	ob_start(); ?>
   	<?php if($height_setting == 'imgsize') { 
						$the_image = aq_resize($image, $width, null, false, false);
						if(empty($the_image[0])) {$the_image[0] = $image; $the_image[1] = null; $the_image[2] = null;} ?>
							<div class="<?php echo esc_attr($itemsize);?> kad-animation image-menu-image-size" data-animation="fade-in" data-delay="150">
						        	<?php if(!empty($link)) {echo '<a href="'.esc_attr($link).'" class="homepromolink" target="'.esc_attr($target).'">';} ?>
						        		<div class="image_menu_hover_class"></div>
			                    		<img src="<?php echo esc_url($the_image[0]); ?>" width="<?php echo esc_attr($the_image[1]); ?>" height="<?php echo esc_attr($the_image[2]); ?>" alt="<?php echo esc_attr($title);?>" />
								        <div class="image_menu_content">
			                            	<div class="image_menu_message">	
								        		<?php if (!empty($title)) {echo '<h4>'.$title.'</h4>';} ?>
								            	<?php if (!empty($description)) {echo '<h5>'.$description.'</h5>';}?>
								            </div>
								        </div>
						        	<?php if(!empty($link)) {echo '</a>'; }?>
						        </div>
	<?php } else { 
			$the_image = aq_resize($image, $width, $height, true);
			if(empty($the_image)) {$the_image = $image;}?>
		        <div class="<?php echo esc_attr($itemsize);?> kad-animation" data-animation="fade-in" data-delay="150">
		        	<?php if(!empty($link)){echo '<a href="'.esc_url($link).'" '.esc_attr($linktarget).' class="homepromolink">';} ?>
				        <div class="infobanner" style="background: url(<?php echo esc_url($the_image); ?>) center center no-repeat; height:<?php echo esc_attr($height) ?>px; <?php echo 'background-size:cover;';?>">
				        	<div class="home-message" style="height:<?php echo esc_attr($height) ?>px;">
				        		<?php if (!empty($title)) echo '<h4>'.$title.'</h4>'; ?>
				            	<?php if (!empty($description)) echo '<h5>'.$description.'</h5>';?>
				            </div>
				        </div>
		        	<?php if(!empty($link)) echo '</a>'; ?>
		        </div>
    <?php }  
    $output = ob_get_contents();
	ob_end_clean();
	
	return $output;
}
