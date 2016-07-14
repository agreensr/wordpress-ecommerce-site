<?php
define( 'OPTIONS_SLUG', 'virtue_premium' );
define( 'LANGUAGE_SLUG', 'virtue' );
load_theme_textdomain('virtue', get_template_directory() . '/languages');
/*
 * Init Theme Options
 */
require_once locate_template('/themeoptions/framework.php');          		// Options framework
require_once locate_template('/themeoptions/options.php');          		// Options framework
require_once locate_template('/themeoptions/options/virtue_extension.php'); // Options framework extension
require_once locate_template('/kt_framework/extensions.php');        		// Remove options from the admin

/*
 * Init Theme Startup/Core utilities
 */
require_once locate_template('/lib/utils.php');           		            // Utility functions
require_once locate_template('/lib/init.php');            					// Initial theme setup and constants
require_once locate_template('/lib/sidebar.php');         					// Sidebar class
require_once locate_template('/lib/config.php');          					// Configuration
require_once locate_template('/lib/cleanup.php');        					// Cleanup
require_once locate_template('/lib/custom-nav.php');        				// Nav Options
require_once locate_template('/lib/nav.php');            					// Custom nav modifications
require_once locate_template('/lib/metaboxes.php');     					// Custom metaboxes
require_once locate_template('/lib/gallery_metabox.php');     				// Custom Gallery metaboxes
require_once locate_template('/lib/taxonomy-meta-class.php');   			// Taxonomy meta boxes
require_once locate_template('/lib/taxonomy-meta.php');         			// Taxonomy meta boxes
require_once locate_template('/lib/comments.php');        					// Custom comments modifications
require_once locate_template('/lib/post-types.php');      					// Post Types
require_once locate_template('/lib/Mobile_Detect.php');        				// Mobile Detect
require_once locate_template('/lib/aq_resizer.php');      					// Resize on the fly
require_once locate_template('/lib/kt-plugins-activate.php');   			// Plugin Activation
require_once locate_template('/kt_framework/status.php');   			// System status

/*
 * Init Shortcodes
 */
require_once locate_template('/lib/kad_shortcodes/shortcodes.php');      					// Shortcodes
require_once locate_template('/lib/kad_shortcodes/carousel_shortcodes.php');   				// Carousel Shortcodes
require_once locate_template('/lib/kad_shortcodes/custom_carousel_shortcodes.php');   		// Carousel Shortcodes
require_once locate_template('/lib/kad_shortcodes/testimonial_shortcodes.php');   			// Carousel Shortcodes
require_once locate_template('/lib/kad_shortcodes/testimonial_form_shortcode.php');   		// Carousel Shortcodes
require_once locate_template('/lib/kad_shortcodes/blog_shortcodes.php');   					// Blog Shortcodes
require_once locate_template('/lib/kad_shortcodes/image_menu_shortcodes.php'); 				// image menu Shortcodes
require_once locate_template('/lib/kad_shortcodes/google_map_shortcode.php');  				// Map Shortcodes
require_once locate_template('/lib/kad_shortcodes/portfolio_shortcodes.php'); 				// Portfolio Shortcodes
require_once locate_template('/lib/kad_shortcodes/portfolio_type_shortcodes.php'); 			// Portfolio Shortcodes
require_once locate_template('/lib/kad_shortcodes/staff_shortcodes.php'); 					// Staff Shortcodes
require_once locate_template('/lib/kad_shortcodes/gallery.php');      						// Gallery Shortcode

/*
 * Init Widgets
 */
require_once locate_template('/lib/premium_widgets.php'); 					// Gallery Widget
require_once locate_template('/lib/widgets.php');         					// Sidebars and widgets

/*
 * Template Hooks
 */
require_once locate_template('/lib/custom.php');          					// Custom functions
require_once locate_template('/lib/authorbox.php');         				// Author box
require_once locate_template('/lib/breadcrumbs.php');         				// Breadcrumbs
require_once locate_template('/lib/template_hooks.php'); 					// Template Hooks
require_once locate_template('/lib/template_hooks/posts.php'); 				// Posts Template Hooks
require_once locate_template('/lib/template_hooks/portfolio.php'); 			// Portfolio Template Hooks
require_once locate_template('/lib/template_hooks/page.php'); 				// Portfolio Template Hooks
require_once locate_template('/lib/custom-woocommerce.php'); 				// Woocommerce functions

/*
 * Load Scripts
 */
require_once locate_template('/lib/admin_scripts.php');    					// Icon functions
require_once locate_template('/lib/scripts.php');        					// Scripts and stylesheets
require_once locate_template('/lib/custom_css.php'); 						// Fontend Custom CSS

/*
 * Updater
 */
require_once locate_template('/lib/wp-updates-theme.php');
$KT_UpdateChecker = new ThemeUpdateChecker('virtue_premium', 'https://kernl.us/api/v1/theme-updates/567242b41c90572e711087ef/');

/*
 * Admin Shortcode Btn
 */
function virtue_shortcode_init() {
	if(is_admin()){ if(kad_is_edit_page()){require_once locate_template('/lib/kad_shortcodes.php');	}}
}
add_action('init', 'virtue_shortcode_init');


