<?php

/**
 * Admin Menu Key Class
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class kt_api_manager_menu {

	private $kt_api_manager_key;
	public function __construct() {

		$this->kt_api_manager_key = kt_api_m()->kt_api_manager_key;

		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'load_settings' ) );
	}

	// Add option page menu
	public function add_menu() {
		$page = add_options_page( __( kt_api_m()->kt_settings_menu_title, 'virtue' ), __( kt_api_m()->kt_settings_menu_title, 'virtue' ), 'manage_options', kt_api_m()->kt_activation_tab_key, array( $this, 'config_page'));
		add_action( 'admin_print_styles-' . $page, array( $this, 'css_scripts' ) );
	}
	public function config_page() {

		$settings_tabs = array( kt_api_m()->kt_activation_tab_key => __( kt_api_m()->kt_menu_tab_activation_title, 'virtue' ), kt_api_m()->kt_deactivation_tab_key => __( kt_api_m()->kt_menu_tab_deactivation_title, 'virtue' ) );
		$current_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : kt_api_m()->kt_activation_tab_key;
		$tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : kt_api_m()->kt_activation_tab_key;
		?>
		<div class='wrap'>
			<?php screen_icon(); ?>
			<h2><?php _e( kt_api_m()->kt_settings_title, 'virtue' ); ?></h2>

			<h2 class="nav-tab-wrapper">
			<?php
				foreach ( $settings_tabs as $tab_page => $tab_name ) {
					$active_tab = $current_tab == $tab_page ? 'nav-tab-active' : '';
					echo '<a class="nav-tab ' . $active_tab . '" href="?page=' . kt_api_m()->kt_activation_tab_key . '&tab=' . $tab_page . '">' . $tab_name . '</a>';
				}
			?>
			</h2>
				<form action='options.php' method='post'>
					<div class="main">
				<?php
					if( $tab == kt_api_m()->kt_activation_tab_key ) {
							settings_fields( kt_api_m()->kt_data_key );
							do_settings_sections( kt_api_m()->kt_activation_tab_key );
							submit_button( __( 'Save Changes', 'virtue' ) );
					} else {
							settings_fields( kt_api_m()->kt_deactivate_checkbox );
							do_settings_sections( kt_api_m()->kt_deactivation_tab_key );
							submit_button( __( 'Save Changes', 'virtue' ) );
					}
				?>
					</div>
				</form>
			</div>
			<?php
	}

	// Register settings
	public function load_settings() {

		register_setting( kt_api_m()->kt_data_key, kt_api_m()->kt_data_key, array( $this, 'validate_options' ) );

		// API Key
		add_settings_section( kt_api_m()->kt_api_key, __( 'Update API License Activation', 'virtue' ), array( $this, 'kt_api_key_text' ), kt_api_m()->kt_activation_tab_key );
		add_settings_field( kt_api_m()->kt_api_key, __( 'Update API License Key', 'virtue' ), array( $this, 'kt_api_key_field' ), kt_api_m()->kt_activation_tab_key, kt_api_m()->kt_api_key );
		add_settings_field( kt_api_m()->kt_activation_email, __( 'Update API License email', 'virtue' ), array( $this, 'kt_api_email_field' ), kt_api_m()->kt_activation_tab_key, kt_api_m()->kt_api_key );

		// Activation settings
		register_setting( kt_api_m()->kt_deactivate_checkbox, kt_api_m()->kt_deactivate_checkbox, array( $this, 'kt_license_key_deactivation' ) );
		add_settings_section( 'deactivate_button', __( 'API License Deactivation', 'virtue' ), array( $this, 'kt_deactivate_text' ), kt_api_m()->kt_deactivation_tab_key );
		add_settings_field( 'deactivate_button', __( 'Deactivate API License Key', 'virtue' ), array( $this, 'kt_deactivate_textarea' ), kt_api_m()->kt_deactivation_tab_key, 'deactivate_button' );

	}

	// Provides text for api key section
	public function kt_api_key_text() {
		echo __('Activating your license allows for updates to theme and bundled plugins. If you need your api key you will find it by logging in here:', 'virtue') . ' <a href="https://www.kadencethemes.com/my-account/" target="_blank">kadencethemes.com/my-account/</a>';
		echo '<input type="hidden" value="'.kt_api_m()->kt_instance_id.'">';
		
	}

	// Outputs API License text field
	public function kt_api_key_field() {

		echo "<input id='api_key' name='" . kt_api_m()->kt_data_key . "[" . kt_api_m()->kt_api_key ."]' size='25' type='text' value='" . kt_api_m()->kt_options[kt_api_m()->kt_api_key] . "' />";
		if ( kt_api_m()->kt_options[kt_api_m()->kt_api_key] ) {
			echo '<span class="ktap-icon-pos"><i class="icon-checkmark" style="font-size:20px; color:green;"></i></span>';
		} else {
			echo '<span class="ktap-icon-pos"><i class="icon-warning" style="font-size:20px; color:orange;"></a></span>';
		}
	}

	// Outputs API License email text field
	public function kt_api_email_field() {

		echo "<input id='activation_email' name='" . kt_api_m()->kt_data_key . "[" . kt_api_m()->kt_activation_email ."]' size='25' type='text' value='" . kt_api_m()->kt_options[kt_api_m()->kt_activation_email] . "' />";
		if ( kt_api_m()->kt_options[kt_api_m()->kt_activation_email] ) {
			echo '<span class="ktap-icon-pos"><i class="icon-checkmark" style="font-size:20px; color:green;"></i></span>';
		} else {
			echo '<span class="ktap-icon-pos"><i class="icon-warning" style="font-size:20px; color:orange;"></a></span>';
		}
	}

	// Sanitizes and validates all input and output for Dashboard
	public function validate_options( $input ) {

		// Load existing options, validate, and update with changes from input before returning
		$options = kt_api_m()->kt_options;

		$options[kt_api_m()->kt_api_key] = trim( $input[kt_api_m()->kt_api_key] );
		$options[kt_api_m()->kt_activation_email] = trim( $input[kt_api_m()->kt_activation_email] );

		/**
		  * Plugin Activation
		  */
		$api_email = trim( $input[kt_api_m()->kt_activation_email] );
		$api_key = trim( $input[kt_api_m()->kt_api_key] );

		$activation_status = get_option( kt_api_m()->kt_activated_key );
		$checkbox_status = get_option( kt_api_m()->kt_deactivate_checkbox );

		$current_api_key = kt_api_m()->kt_options[kt_api_m()->kt_api_key];

		// Should match the settings_fields() value
		if ( $_REQUEST['option_page'] != kt_api_m()->kt_deactivate_checkbox ) {

			if ( $activation_status == 'Deactivated' || $activation_status == '' || $api_key == '' || $api_email == '' || $checkbox_status == 'on' || $current_api_key != $api_key  ) {
				if ( $current_api_key != $api_key ) {
					$this->replace_license_key( $current_api_key );
				}

				$args = array(
					'email' 		=> $api_email,
					'licence_key' 	=> $api_key,
					);

				$activate_results = json_decode( $this->kt_api_manager_key->activate( $args ), true );

				if ( $activate_results['activated'] === true ) {
					add_settings_error( 'activate_text', 'activate_msg', __( 'Theme activated. ', 'virtue' ), 'updated' );
					update_option( kt_api_m()->kt_activated_key, 'Activated' );
					update_option( kt_api_m()->kt_deactivate_checkbox, 'off' );
					update_option( 'kt_api_active_order', $activate_results['activation_extra']['order_id']);
				}

				if ( $activate_results == false ) {
					add_settings_error( 'api_key_check_text', 'api_key_check_error', __( 'Connection failed to the License Key API server. Make sure your host servers php version has the curl module installed and enabled.', 'virtue' ), 'error' );
					$options[kt_api_m()->kt_api_key] = '';
					$options[kt_api_m()->kt_activation_email] = '';
					update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
					update_option( 'kt_api_active_order', '');
				}

				if ( isset( $activate_results['code'] ) ) {

					switch ( $activate_results['code'] ) {
						case '100':
							add_settings_error( 'api_email_text', 'api_email_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options[kt_api_m()->kt_activation_email] = '';
							$options[kt_api_m()->kt_api_key] = '';
							// Deletes all data
							kt_api_m()->uninstall();
							update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
						break;
						case '101':
							add_settings_error( 'api_key_text', 'api_key_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options[kt_api_m()->kt_api_key] = '';
							$options[kt_api_m()->kt_activation_email] = '';
							// Deletes all data
							kt_api_m()->uninstall();
							update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
						break;
						case '102':
							add_settings_error( 'api_key_purchase_incomplete_text', 'api_key_purchase_incomplete_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options[kt_api_m()->kt_api_key] = '';
							$options[kt_api_m()->kt_activation_email] = '';
							// Deletes all data
							kt_api_m()->uninstall();
							update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
						break;
						case '103':
							add_settings_error( 'api_key_exceeded_text', 'api_key_exceeded_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options[kt_api_m()->kt_api_key] = '';
							$options[kt_api_m()->kt_activation_email] = '';
							// Deletes all data
							kt_api_m()->uninstall();
							update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
						break;
						case '104':
							add_settings_error( 'api_key_not_activated_text', 'api_key_not_activated_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options[kt_api_m()->kt_api_key] = '';
							$options[kt_api_m()->kt_activation_email] = '';
							// Deletes all data
							kt_api_m()->uninstall();
							update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
						break;
						case '105':
							add_settings_error( 'api_key_invalid_text', 'api_key_invalid_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options[kt_api_m()->kt_api_key] = '';
							$options[kt_api_m()->kt_activation_email] = '';
							// Deletes all data
							kt_api_m()->uninstall();
							update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
						break;
						case '106':
							add_settings_error( 'sub_not_active_text', 'sub_not_active_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options[kt_api_m()->kt_api_key] = '';
							$options[kt_api_m()->kt_activation_email] = '';
							// Deletes all data
							kt_api_m()->uninstall();
							update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
						break;
					}

				}

			}

		}

		return $options;
	}

	// Deactivate the current license key before activating the new license key
	public function replace_license_key( $current_api_key ) {

		$args = array(
			'email' 		=> kt_api_m()->kt_options[kt_api_m()->kt_activation_email],
			'licence_key' 	=> $current_api_key,
			);

		$reset = $this->kt_api_manager_key->deactivate( $args ); // reset license key activation

		if ( $reset == true ) {
			return true;
		} else {
			return add_settings_error( 'not_deactivated_text', 'not_deactivated_error', __( 'The license could not be deactivated. Use the License Deactivation tab to manually deactivate the license before activating a new license.', 'virtue' ), 'updated' );
		}
	}

	// Deactivates the license key to allow key to be used on another blog
	public function kt_license_key_deactivation( $input ) {

		$activation_status = get_option( kt_api_m()->kt_activated_key );

		$args = array(
			'email' 		=> kt_api_m()->kt_options[kt_api_m()->kt_activation_email],
			'licence_key' 	=> kt_api_m()->kt_options[kt_api_m()->kt_api_key],
			);

		// For testing activation status_extra data
		// $activate_results = json_decode( $this->kt_api_manager_key->status( $args ), true );
		// print_r($activate_results); exit;

		$options = ( $input == 'on' ? 'on' : 'off' );

		if ( $options == 'on' && $activation_status == 'Activated' && kt_api_m()->kt_options[kt_api_m()->kt_api_key] != '' && kt_api_m()->kt_options[kt_api_m()->kt_activation_email] != '' ) {

			// deactivates license key activation
			$activate_results = json_decode( $this->kt_api_manager_key->deactivate( $args ), true );

			// Used to display results for development
			//print_r($activate_results); exit();

			if ( $activate_results['deactivated'] == true ) {
				$update = array(
					kt_api_m()->kt_api_key => '',
					kt_api_m()->kt_activation_email => ''
					);

				$merge_options = array_merge( kt_api_m()->kt_options, $update );

				update_option( kt_api_m()->kt_data_key, $merge_options );

				update_option( kt_api_m()->kt_activated_key, 'Deactivated' );
				update_option( 'kt_api_active_order', '');

				// Deletes all data using the Deactivate API License Key checkbox
				kt_api_m()->uninstall();

				add_settings_error( 'kt_deactivate_text', 'deactivate_msg', __( 'Theme license deactivated. ', 'virtue' ) . "{$activate_results['activations_remaining']}.", 'updated' );

				return $options;
			}

			if ( isset( $activate_results['code'] ) ) {

				switch ( $activate_results['code'] ) {
					case '100':
						add_settings_error( 'api_email_text', 'api_email_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
						$options[kt_api_m()->kt_activation_email] = '';
						$options[kt_api_m()->kt_api_key] = '';
						update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
					break;
					case '101':
						add_settings_error( 'api_key_text', 'api_key_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
						$options[kt_api_m()->kt_api_key] = '';
						$options[kt_api_m()->kt_activation_email] = '';
						update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
					break;
					case '102':
						add_settings_error( 'api_key_purchase_incomplete_text', 'api_key_purchase_incomplete_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
						$options[kt_api_m()->kt_api_key] = '';
						$options[kt_api_m()->kt_activation_email] = '';
						update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
					break;
					case '103':
							add_settings_error( 'api_key_exceeded_text', 'api_key_exceeded_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options[kt_api_m()->kt_api_key] = '';
							$options[kt_api_m()->kt_activation_email] = '';
							update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
					break;
					case '104':
							add_settings_error( 'api_key_not_activated_text', 'api_key_not_activated_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options[kt_api_m()->kt_api_key] = '';
							$options[kt_api_m()->kt_activation_email] = '';
							update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
					break;
					case '105':
							add_settings_error( 'api_key_invalid_text', 'api_key_invalid_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options[kt_api_m()->kt_api_key] = '';
							$options[kt_api_m()->kt_activation_email] = '';
							update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
					break;
					case '106':
							add_settings_error( 'sub_not_active_text', 'sub_not_active_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options[kt_api_m()->kt_api_key] = '';
							$options[kt_api_m()->kt_activation_email] = '';
							update_option( kt_api_m()->kt_options[kt_api_m()->kt_activated_key], 'Deactivated' );
					break;
				}

			}

		} else {

			return $options;
		}

	}

	public function kt_deactivate_text() {
	}

	public function kt_deactivate_textarea() {

		echo '<input type="checkbox" id="' . kt_api_m()->kt_deactivate_checkbox . '" name="' . kt_api_m()->kt_deactivate_checkbox . '" value="on"';
		echo checked( get_option( kt_api_m()->kt_deactivate_checkbox ), 'on' );
		echo '/>';
		?><span class="description"><?php _e( 'Deactivates an API License Key.', 'virtue' ); ?></span>
		<?php
	}

	// Loads admin style sheets
	public function css_scripts() {

		wp_register_style( kt_api_m()->kt_data_key . '-css', get_template_directory_uri() . '/lib/classes/kt_api_manage.css', array(), kt_api_m()->version, 'all');
		wp_enqueue_style( kt_api_m()->kt_data_key . '-css' );
	}

}

new kt_api_manager_menu();
