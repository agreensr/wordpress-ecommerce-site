<?php 
//Shortcode for Custom Carousels
function kad_custom_carousel_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'columns' => '4',
		'speed' => '9000',
		'scroll' => '1'
), $atts));
	$carousel_rn = (rand(10,100));
	if(empty($scroll) || $scroll == 1) {$scroll = '1';} else {$scroll = '';}

ob_start(); 
			if ($columns == '2') {
				$md = 2; $sm = 2; $xs = 1; $ss = 1;
			} else if ($columns == '1') {
				$md = 1; $sm = 1; $xs = 1; $ss = 1;
			} else if ($columns == '3'){ 
				$md = 3; $sm = 3; $xs = 2; $ss = 1;
			} else if ($columns == '6'){
				$md = 6; $sm = 4; $xs = 3; $ss = 2;
			} else if ($columns == '5'){ 
				$md = 5; $sm = 4; $xs = 3; $ss = 2;
			} else {
				$md = 4; $sm = 3; $xs = 2; $ss = 1;
			} ?>
			<div class="carousel_outerrim kad-animation" data-animation="fade-in" data-delay="0">
				<div class="home-margin fredcarousel">
					<div id="carouselcontainer-<?php echo esc_attr($carousel_rn); ?>" class="rowtight fadein-carousel">
						<div id="carousel-<?php echo esc_attr($carousel_rn); ?>" class="clearfix caroufedselclass initcaroufedsel" data-carousel-container="#carouselcontainer-<?php echo esc_attr($carousel_rn); ?>" data-carousel-transition="700" data-carousel-scroll="<?php echo esc_attr($scroll);?>" data-carousel-auto="true" data-carousel-speed="<?php echo esc_attr($speed);?>" data-carousel-id="<?php echo esc_attr($carousel_rn); ?>" data-carousel-md="<?php echo esc_attr($md);?>" data-carousel-sm="<?php echo esc_attr($sm);?>" data-carousel-xs="<?php echo esc_attr($xs);?>" data-carousel-ss="<?php echo esc_attr($ss);?>">
								<?php echo do_shortcode($content); ?>
            			</div>
            		</div>
					<div class="clearfix"></div>
            		<a id="prevport-<?php echo esc_attr($carousel_rn); ?>" class="prev_carousel icon-arrow-left" href="#"></a>
					<a id="nextport-<?php echo esc_attr($carousel_rn); ?>" class="next_carousel icon-arrow-right" href="#"></a>
				</div>
			</div>		
	<?php  $output = ob_get_contents();
		ob_end_clean();
	return $output;
}

//Shortcode for Custom Carousel Items
function kad_custom_carousel_item_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'columns' => '',
), $atts));
	if(empty($columns)) {$columns = '4';}

ob_start(); 
		if ($columns == '2') {
			$itemsize = 'tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; 
		} else if ($columns == '1') {
			$itemsize = 'tcol-lg-12 tcol-md-12 tcol-sm-12 tcol-xs-12 tcol-ss-12';
		} else if ($columns == '3'){ 
			$itemsize = 'tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';
		} else if ($columns == '6'){
			$itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6';
		} else if ($columns == '5'){ 
			$itemsize = 'tcol-lg-25 tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6';
		} else {
			$itemsize = 'tcol-lg-3 tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12';
		} ?>
							<div class="<?php echo esc_attr($itemsize); ?> kad_customcarousel_item">
								<div class="carousel_item grid_item">
								<?php echo do_shortcode($content); ?>
								</div>
							</div>
	<?php  $output = ob_get_contents();
		ob_end_clean();
	return $output;
}