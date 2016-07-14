<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Woo_Extra_Flat_Rate
 * @subpackage Woo_Extra_Flat_Rate/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Extra_Flat_Rate
 * @subpackage Woo_Extra_Flat_Rate/admin
 * @author     Multidots <wordpress@multidots.com>
 */
class Woo_Extra_Flat_Rate_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Extra_Flat_Rate_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Extra_Flat_Rate_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-extra-flat-rate-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Extra_Flat_Rate_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Extra_Flat_Rate_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-extra-flat-rate-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Add Extra Setting tab
	 *
	 * @since    1.0.0
	 */
    public function extra_flat_rate_admin_init_own(){
		require_once 'partials/woo-extra-flat-rate-admin-display.php';
		$admin = new WC_Settings_Extra_Shipping_Methods();
    }
    
   public function welcome_screen_do_activation_redirect() {
  // Bail if no activation redirect
    if ( ! get_transient( '_woo_extra_flat_rate_welcome_screen' ) ) {
    return;
  }

  // Delete the redirect transient
  delete_transient( '_woo_extra_flat_rate_welcome_screen' );

  // Bail if activating from network, or bulk
  if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
    return;
  }

  // Redirect to bbPress about page
  wp_safe_redirect( add_query_arg( array( 'page' => 'woo-extra-flat-about&tab=about' ), admin_url( 'index.php' ) ) );

}

public function welcome_screen_pages() {
  add_dashboard_page('Welcome To WooCommerce Extra Flat Rate','Welcome To WooCommerce Extra Flat Rate', 'read','woo-extra-flat-about',array($this,'welcome_screen_content' ));
}

public function welcome_screen_content() {
  ?>
  <div class="wrap">
  <div class="woo-extra-flat-welcome-content">
	    <h1>Welcome to WooCommerce Extra Flat Rate</h1>
	
	    <p class="about-description">
	      Congratulations! You can add multiple shipping in your wooCommerce website as well as you can add/remove it as per your requirement.
	    </p>
	    <h3><?php _e('Add New flat Rate shipping option in your WooCommerce site.','woo-extra-flat');?></h3>
	    <p class="about-description"><?php _e('WooCommerce Extra Flat Rate Shipping Method For WooCommerce plugin provides you an interface in WooCommerce setting section from admin side. So admin can add Multiple Shipping option(Extra Flat Rate Shipping) or remove any existing shipping from the backend. Admin set options will be displayed from the front side. So the user can choose shipping method based on that.','woo-extra-flat');?></p>
	    <p class="about-description"><?php _e('This plugin is for those users who wants to use Multiple shipping on the website. By using this plugin, You can add multiple shipping in your wooCommerce website as well as you can add/remove it as per your requirement.','woo-extra-flat');?></p>
	      <div class="return-to-dashboard">
	           <p><a href="<?php echo home_url('wp-admin/admin.php?page=wc-settings&tab=shipping_methods'); ?>"><?php _e('Go to WooCommerce Extra Flat Rate Settings', 'woo-extra-flat'); ?></a></p>
	        </div>
	  </div>
  </div>
  <?php
}
function welcome_screen_remove_menus() {
    remove_submenu_page( 'index.php', 'welcome-screen-about' );
}
 public function admin_notices_extra_flat_rate() {
  ?>
 <div class="notice error woo-extra-flat-rate-notice is-dismissible" >
        <div><p><?php _e( 'You are currently using the free version of WooCommerce Flat Rate Plugin. To enjoy extra features, buy the Pro version <a href="http://bit.ly/Woo-org" target="_blank">Advance Flat Rate Shipping Method</a>', 'my-text-domain' ); ?></p></div>
    </div>
  <?php
}

function my_dismiss_flatrate_notice() {

	update_option( 'woo-extra-flat-rate-notice-dismissed','1' );
		
	
}

}
