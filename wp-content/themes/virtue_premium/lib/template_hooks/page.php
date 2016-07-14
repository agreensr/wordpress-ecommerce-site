<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
add_action( 'kt_header_after', 'kt_mobile_header', 20 );
function kt_mobile_header() {
	global $virtue_premium;
	if(isset($virtue_premium['mobile_header']) && $virtue_premium['mobile_header'] == '1') {
		get_template_part('templates/mobile', 'header'); 
	}
}