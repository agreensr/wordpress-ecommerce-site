<?php
function kad_testimonial_form($atts, $content = null) {
	extract(shortcode_atts(array(
		'location' => false,
		'position' => false,
		'link' => false,
		'image' => false,
		'login' => false,
		'email' => '',
		'name_label' => __('Name', 'virtue'),
		'testimonial_label' => __('Testimonial', 'virtue'),
		'location_label' => __('Location - optional', 'virtue'),
		'position_label' => __('Position or Company - optional', 'virtue'),
		'link_label' => __('Link - optional', 'virtue'),
		'image_label' => __('Image Upload - optional', 'virtue'),
		'submit_label' => __('Submit', 'virtue'),
		'math_error' => __('Check your math.', 'virtue'),
		'name_error' => __('Please enter your name.', 'virtue'),
		'content_error' => __('Please add testimonial content.', 'virtue'),
		'error_message' => __('Sorry, an error occured.', 'virtue'),
		'login_message' => __('You must be logged in to submit an testimonial.', 'virtue'),
		'success_message' => __('Thank you for submitting your testimonial! It is now awaiting approval from the site admnistator. Thank you!', 'virtue'),
), $atts));
	global $post;
	ob_start();
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
    	$.extend($.validator.messages, {
	        required: "<?php echo __('This field is required.', 'virtue'); ?>",
			email: "<?php echo __('Please enter a valid email address.', 'virtue'); ?>",
		 });
		$("#kad-feedback-new-post").validate();
	});
	</script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery.validate-ck.js"></script>
	<?php 
	if(isset($_POST['submitted']) && wp_verify_nonce( $_POST['post-title-nonce'], 'post-title' ) ) {
		$user_id = null;
		$post_title = $_POST['post-title'];
		$post_content = $_POST['posttext'];
 
 		if(empty($_POST['post-verify'])) { $kt_feed_error = true; $kad_captchaError = $math_error; }
 		if(md5($_POST['post-verify']) != $_POST['hval']) { $kt_feed_error = true; $kad_captchaError = $math_error;}
		if (empty($post_title)) {$kt_feed_error = true;  $nameError = $name_error;}
		if (empty($post_content)) {$kt_feed_error = true; $contentError = $content_error;}
 
		if (!isset($kt_feed_error)){
 
			$post_id = wp_insert_post( array(
				'post_author'	=> $user_id,
				'post_title'	=> $post_title,
				'post_type'     => 'testimonial',
				'post_content'	=> $post_content,
				'post_status'	=> 'pending'
				) );
				if(isset($_POST['post-location'])) {	
					update_post_meta($post_id, '_kad_testimonial_location', esc_attr(strip_tags($_POST['post-location'])));
					}	
				if(isset($_POST['post-company'])) {	
					update_post_meta($post_id, '_kad_testimonial_occupation', esc_attr(strip_tags($_POST['post-company'])));
					}	
				if(isset($_POST['post-link'])) {	
					update_post_meta($post_id, '_kad_testimonial_link', esc_attr(strip_tags($_POST['post-link'])));
					}
				if(isset($_FILES['post-img'])) {	
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
	
				$attachment_id = media_handle_upload('post-img', $post_id);
				if ( is_wp_error( $attachment_id ) ) {

					} else {
						set_post_thumbnail($post_id, $attachment_id);
					}
				unset($_FILES);
       			}
       	if(!empty($email)){
			$emailTo = $email;
		} else {
			$emailTo = get_option('admin_email');
		}
		$sitename = get_bloginfo('name');
		$subject = '['.$sitename . __(" Testimonial Post", "virtue").'] '. __("From ", "virtue"). $post_title;
		$body = __('Name', 'virtue').": $post_title \n\nComments: $post_content";
		$headers = __("From", "virtue").': '.$post_title;

		wp_mail($emailTo, $subject, $body, $headers);		
 		$postCreated = true;
		}
	}

		?>
<div id="kt-feedback-postbox" class="testimonial-form-container">
		<?php if(isset($postCreated) && $postCreated == true) { ?>
							<div class="thanks">
								<p><?php echo $success_message;?></p>
							</div>
		<?php } else { ?>
			<?php if(isset($kt_feed_error)) { ?>
				<p class="kt-error"><?php echo $error_message; ?><p>
			<?php } ?>
			<?php if($login && !is_user_logged_in()) { ?>
   					<p><?php echo $login_message;?></p> 
			<?php } else { ?>
		<div class="kt-feedback-inputarea">
			<form id="kad-feedback-new-post" name="new_post" method="post" enctype="multipart/form-data" action="<?php the_permalink(); ?>">
				<p><label><?php echo $name_label;?></label>
					<input type="text" class="full required requiredField" value="<?php if(isset($_POST['post-title'])) echo $_POST['post-title'];?>" id="kt-feedback-post-title" name="post-title" />
					<?php if(isset($nameError)) { ?><label class="error"><?php $nameError;?></label><?php } ?>
				</p>
				<p><label><?php echo $testimonial_label;?></label>
					<textarea class="required requiredField" name="posttext" id="kt-feedback-post-text" cols="60" rows="10"><?php if(isset($_POST['posttext'])) echo $_POST['posttext'];?></textarea>
					<?php if(isset($contentError)) { ?><label class="error"><?php $contentError;?></label><?php } ?>
				</p>
				<?php if($location) {?>
				<p><label><?php echo $location_label;?></label>
					<input type="text" class="full" id="kt-feedback-post-location" value="<?php if(isset($_POST['post-location'])) echo $_POST['post-location'];?>" name="post-location" />
				</p>
				<?php } 
				if($position) {?>
				<p><label><?php echo $position_label; ?></label>
					<input type="text" class="full" value="<?php if(isset($_POST['post-company'])) echo $_POST['post-company'];?>" id="kt-feedback-post-company" name="post-company" />
				</p>
				<?php } 
				if($link) {?>
				<p><label><?php echo $link_label;?></label>
					<input type="text" class="full" id="kt-feedback-post-link" value="<?php if(isset($_POST['post-link'])) echo $_POST['post-link'];?>" name="post-link" />
				</p>
				<?php } 
				if($image) {?>
				<p><label><?php echo $image_label;?></label>
					<input type="file" class="full kad_file_input" id="post-img"  multiple="false" value="<?php if(isset($_POST['post-img'])) echo $_POST['post-img'];?>" name="post-img" />
				</p>
				<?php } ?>

			<?php   $one = rand(5, 50);
					$two = rand(1, 9);
					$result = md5($one + $two); ?>

				<p><label><?php echo $one.' + '.$two; ?></label>
					<input type="text" id="kt-feedback-post-verify" class="kad-quarter required requiredField" name="post-verify" />
				<?php if(isset($kad_captchaError)) { ?><label class="error"><?php echo $kad_captchaError;?></label><?php } ?>
				</p>
				<input type="hidden" name="hval" id="hval" value="<?php echo $result;?>" />
				<?php wp_nonce_field('post-title', 'post-title-nonce'); ?>
				<input id="submit" type="submit" class="kad-btn kad-btn-primary" tabindex="3" value="<?php echo $submit_label ?>" />					
				<input type="hidden" name="submitted" id="submitted" value="true" />
			</form>
		</div>
		<?php } }?>
 
</div>
<?php
	// Output the content.
	$output = ob_get_contents();
	ob_end_clean();
 
  return  $output;
}
 
// Add the shortcode to WordPress. 
add_shortcode('kad_testimonial_form', 'kad_testimonial_form');

