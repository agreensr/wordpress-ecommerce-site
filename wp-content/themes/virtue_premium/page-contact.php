<?php
/*
Template Name: Contact
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

 global $post;
	$map 		= get_post_meta( $post->ID, '_kad_contact_map', true );
	$form_math 	= get_post_meta( $post->ID, '_kad_contact_form_math', true );
	$form 		= get_post_meta( $post->ID, '_kad_contact_form', true );
	
	if ($form == 'yes') { ?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {$.extend($.validator.messages, {required: "<?php echo __('This field is required.', 'virtue'); ?>", email: "<?php echo __('Please enter a valid email address.', 'virtue'); ?>",});$("#contactForm").validate();});
		</script>
		<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery.validate-ck.js"></script>
	<?php } 
	if ($map == 'yes') { ?>
		    <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
		    <?php global $post; $address = get_post_meta( $post->ID, '_kad_contact_address', true ); 
							    $maptype = get_post_meta( $post->ID, '_kad_contact_maptype', true ); 
							    $height = get_post_meta( $post->ID, '_kad_contact_mapheight', true );
							    $address2 = get_post_meta( $post->ID, '_kad_contact_address2', true );
							    $address3 = get_post_meta( $post->ID, '_kad_contact_address3', true );
							    $address4 = get_post_meta( $post->ID, '_kad_contact_address4', true );
							    $mapcenter = get_post_meta( $post->ID, '_kad_contact_map_center', true );
							    if($height != '') {$mapheight = $height;} else {$mapheight = 300;}
							    $mapzoom = get_post_meta( $post->ID, '_kad_contact_zoom', true ); 
							    if($mapzoom != '') $zoom = $mapzoom; else $zoom = 15; 
							    if(empty($mapcenter)) {$center = $address;} else {$center = $mapcenter;}
		    ?>
		    <script type="text/javascript">
					jQuery(window).load(function() {
					var isDraggable = jQuery(document).width() > 480 ? true : false;
					jQuery('#map_address').gmap3({
			map: {
			    address:"<?php echo esc_js($center);?>",
				options: {
              		zoom:<?php echo esc_js($zoom);?>,
					draggable: isDraggable,
					mapTypeControl: true,
					mapTypeId: google.maps.MapTypeId.<?php echo esc_js($maptype);?>,
					scrollwheel: false,
					panControl: true,
					rotateControl: false,
					scaleControl: true,
					streetViewControl: true,
					zoomControl: true
				}
			},
			marker:{
            values:[
            		 {address: "<?php echo esc_js($address);?>",
			 	    data:"<div class='mapinfo'>'<?php echo esc_js($address);?>'</div>",
			 		},
			 	<?php if(!empty($address2)) {?> { address: "<?php echo esc_js($address2);?>",
				 	    	data:"<div class='mapinfo'> <?php echo esc_js($address2);?></div>",
				 	},
				 	<?php } ?>
				 	<?php if(!empty($address3)) {?> { address: "<?php echo esc_js($address3);?>",
				 	    	data:"<div class='mapinfo'> <?php echo esc_js($address3);?></div>",
				 	},
				 	<?php } ?>
				 	<?php if(!empty($address4)) {?> { address: "<?php echo esc_js($address4);?>",
				 	    	data:"<div class='mapinfo'><?php echo esc_js($address4);?></div>",
				 	},
				 	<?php } ?>
            ],
            options:{
              draggable: false,
            },
			events:{
              click: function(marker, event, context){
                var map = jQuery(this).gmap3("get"),
                  infowindow = jQuery(this).gmap3({get:{name:"infowindow"}});
                if (infowindow){
                  infowindow.open(map, marker);
                  infowindow.setContent(context.data);
                } else {
                  jQuery(this).gmap3({
                    infowindow:{
                      anchor:marker, 
                      options:{content: context.data}
                    }
                  });
                }
              },
              closeclick: function(){
                var infowindow = jQuery(this).gmap3({get:{name:"infowindow"}});
                if (infowindow){
                  infowindow.close();
                }
			  }
			}
          }
        });        
      });
			</script>
		    <?php echo '<style type="text/css" media="screen">#map_address {height:'.esc_attr($mapheight).'px; margin-bottom:20px;}</style>'; ?>
    <?php } ?>
<?php global $virtue_premium, $post;
if(isset($_POST['submitted'])) {
	if(isset($form_math) && $form_math == 'yes') {
		if(md5($_POST['kad_captcha']) != $_POST['hval']) {
			$kad_captchaError = __('Check your math.', 'virtue');
			$hasError = true;
		}
	}
	if(trim($_POST['contactName']) === '') {
		$nameError = __('Please enter your name.', 'virtue');
		$hasError = true;
	} else {
		$name = trim($_POST['contactName']);
	}

	if(trim($_POST['email']) === '')  {
		$emailError = __('Please enter your email address.', 'virtue');
		$hasError = true;
	} else if (!is_email(trim($_POST['email']))) {
		$emailError = __('You entered an invalid email address.', 'virtue');
		$hasError = true;
	} else {
		$email = trim($_POST['email']);
	}

	if(trim($_POST['comments']) === '') {
		$commentError = __('Please enter a message.', 'virtue');
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$comments = stripslashes(trim($_POST['comments']));
		} else {
			$comments = trim($_POST['comments']);
		}
	}

	if(!isset($hasError)) {
		$name = wp_filter_kses( $name );
		$email = wp_filter_kses( $email );
		$comments = wp_filter_kses( $comments );

		if (isset($virtue_premium['contact_email'])) {
			$emailTo = $virtue_premium['contact_email'];
		} else {
			$emailTo = get_option('admin_email');
		}
		$sitename = get_bloginfo('name');
		$subject = '['.$sitename . '  '. __("Contact", "virtue").'] '. __("From ", "virtue"). $name;
		$body = __('Name', 'virtue').": $name \n\nEmail: $email \n\nComments: $comments";
		$headers = 'Reply-To: ' . $email;

		wp_mail($emailTo, $subject, $body, $headers);
		$emailSent = true;
	}

} ?>
  	<div id="pageheader" class="titleclass">
		<div class="container">
			<?php get_template_part('templates/page', 'header'); ?>
			</div><!--container-->
	</div><!--titleclass-->
<?php if ($map == 'yes') { ?>
            <div id="mapheader" class="titleclass">
            	<div class="container">
		            <div id="map_address">
		            </div>
	            </div><!--container-->
            </div><!--titleclass-->
  <?php } ?>

	<div id="content" class="container">
   		<div class="row">
   		<?php if ($form == 'yes') { ?>
	  		<div id="main" class="col-md-6" role="main"> 
	  	<?php } else { ?>
      		<div id="main" class="col-md-12" role="main">
      	<?php } ?>
      			<div class="entry-content" itemprop="mainContentOfPage">
					<?php get_template_part('templates/content', 'page'); ?>
				</div>
    		</div>
    <?php if ($form == 'yes') { ?>
      		<div class="contactformcase col-md-6">
      			<?php $contactformtitle = get_post_meta( $post->ID, '_kad_contact_form_title', true ); 
      			if (!empty($contactformtitle)) { 
      				echo '<h3>'. __($contactformtitle) .'</h3>';
      			} ?>
				<?php if(isset($emailSent) && $emailSent == true) { 
					do_action('kt_contact_email_sent');
					?>
							<div class="thanks">
								<p><?php _e('Thanks, your email was sent successfully.', 'virtue');?></p>
							</div>
				<?php } else { ?>
							<?php if(isset($hasError)) { ?>
								<p class="error"><?php _e('Sorry, an error occured.', 'virtue');?><p>
							<?php } ?>

						<form action="<?php the_permalink(); ?>" id="contactForm" method="post">
							<div class="contactform">
							<p>
								<label for="contactName"><b><?php _e('Name:', 'virtue');?></b></label>
								<input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])){ echo esc_attr($_POST['contactName']);}?>" class="required requiredField full" />
								<?php if(isset($nameError)) { ?>
									<label class="error"><?php esc_html($nameError);?></label>
								<?php } ?>
							</p>
							<p>
								<label for="email"><b><?php _e('Email:', 'virtue'); ?></b></label>
								<input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])){ echo esc_attr($_POST['email']); }?>" class="required requiredField email full" />
								<?php if(isset($emailError)) { ?>
									<label class="error"><?php echo esc_html($emailError);?></label>
								<?php } ?>
							</p>
							<p><label for="commentsText"><b><?php _e('Message: ', 'virtue'); ?></b></label>
								<textarea name="comments" id="commentsText" rows="10" class="required requiredField"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo esc_textarea(stripslashes($_POST['comments'])); } else { echo esc_textarea($_POST['comments']); } } ?></textarea>
								<?php if(isset($commentError)) { ?><label class="error"><?php echo esc_html($commentError);?></label><?php } ?>
							</p>
							<?php if(isset($form_math) && $form_math == 'yes') {
							   		$one = rand(5, 50);
									$two = rand(1, 9);
									$result = md5($one + $two); ?>
								<p>
									<label for="kad_captcha"><b><?php echo $one.' + '.$two; ?> = </b></label>
									<input type="text" name="kad_captcha" id="kad_captcha" class="required requiredField kad_captcha kad-quarter" />
									<?php if(isset($kad_captchaError)) { ?>
										<label class="error"><?php echo esc_html($kad_captchaError);?></label>
										<?php } ?>
									<input type="hidden" name="hval" id="hval" value="<?php echo esc_attr($result);?>" />
								</p>
							<?php } ?>
							<p>
								<input type="submit" class="kad-btn kad-btn-primary" id="submit" value="<?php _e('Send Email', 'virtue'); ?>" ></input>
							</p>
						</div><!-- /.contactform-->
						<input type="hidden" name="submitted" id="submitted" value="true" />
					</form>
				<?php } ?>
      </div><!--contactform-->
    <?php } ?>