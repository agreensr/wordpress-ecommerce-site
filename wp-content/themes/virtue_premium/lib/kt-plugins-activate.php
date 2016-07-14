<?php

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'kadence_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 */
function kadence_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	global $virtue_premium;
	$extensions = array();
	$addons = '';
	if(isset($virtue_premium['kt_revslider_notice']) && $virtue_premium['kt_revslider_notice'] == 1) {
			$addons[] = array(
				'name'     				=> 'Revolution Slider',
				'slug'     				=> 'revslider',
				'source'   				=> 'https://s3.amazonaws.com/ktupdates/api/54356163417689/revslider.zip',
				'required' 				=> false,
				'version' 				=> '5.2.5',
				'force_activation' 		=> false, 
				'force_deactivation' 	=> false, 
				'external_url' 			=> '', 
			);
	}
	if(isset($virtue_premium['kt_cycloneslider_notice']) && $virtue_premium['kt_cycloneslider_notice'] == 1) {
			$addons[] = array(
			'name'     				=> 'Cyclone Slider Pro', // The plugin name
			'slug'     				=> 'cyclone-slider-pro', // The plugin slug (typically the folder name)
			'source'   				=> 'https://s3.amazonaws.com/ktupdates/api/524561603141589/cyclone-slider-pro.zip', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '2.10.4', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
			);
	}
	if(isset($virtue_premium['kt_kadenceslider_notice']) && $virtue_premium['kt_kadenceslider_notice'] == 1) {
			$addons[] = array(
			'name'     				=> 'Kadence Slider', // The plugin name
			'slug'     				=> 'kadence-slider', // The plugin slug (typically the folder name)
			'source'   				=> 'https://s3.amazonaws.com/ktupdates/api/524561603141589/kadence-slider.zip', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '1.5.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
			);
	}

	$bundled = array(
		array(
			'name'     				=> 'Page Builder by SiteOrigin', // The plugin name
			'slug'     				=> 'siteorigin-panels', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			),
		array(
			'name'     				=> 'Black Studio TinyMCE Widget', // The plugin name
			'slug'     				=> 'black-studio-tinymce-widget', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			)
	);

	if ( is_array( $addons ) ) {

      foreach ( $addons as $ext => $data ) {

        $extensions[$ext] = array(
          'name'               => $data['name'],
          'slug'               => $data['slug'],
          'source'             => $data['source'],
          'required'           => false,
          'version'            => $data['version'],
          'force_activation'   => false,
          'force_deactivation' => false,
          'external_url'       => '',
        );

      }

    }
	$plugins = array_merge( $bundled, $extensions );

	$theme_text_domain = 'virtue';

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'       		=> 'virtue',         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_slug'  		=> 'themes.php',            // Parent menu slug.
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> false,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Suggested Plugins', 'virtue' ),
			'menu_title'                       			=> __( 'Theme Recommended Plugins', 'virtue' ),
			'installing'                       			=> __( 'Installing Plugin: %s', 'virtue' ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', 'virtue' ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'virtue' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme comes packaged with the following premium plugin: %1$s. Plugin is not required.', 'This theme comes packaged with the following premium plugins: %1$s. Plugins are not required.', 'virtue' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'virtue' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'virtue' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'virtue' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'virtue' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'virtue' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'virtue' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'virtue' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'virtue' ),
			'return'                           			=> __( 'Return to recommended Plugins Installer', 'virtue' ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'virtue' ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'virtue' ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);


	tgmpa( $plugins, $config );

	if( !current_user_can('manage_options') || apply_filters( 'kadence_hide_plugin_notice', false ) ) {
	TGM_Plugin_Activation::$instance->has_notices = false;
	}


}