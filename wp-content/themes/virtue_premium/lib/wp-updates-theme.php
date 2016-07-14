<?php
 
/**
 * Displays an inactive message if the API License Key has not yet been activated
 */
if ( get_option( 'kt_api_manager_virtue_premium_activated' ) != 'Activated' ) {
    add_action( 'admin_notices', 'kt_api_manager::kt_api_m_inactive_notice' );
}

class kt_api_manager {
    public $upgrade_url = 'http://www.kadencethemes.com/';
    public $kt_api_version_name = 'kt_api_version_1_0';
    public $theme_url;
    public $version;
    private $my_theme;
    private $kt_software_product_id;
    public $kt_data_key;
    public $kt_api_key;
    public $kt_activation_email;
    public $kt_product_id_key;
    public $kt_instance_key;
    public $kt_deactivate_checkbox_key;
    public $kt_activated_key;
    public $kt_deactivate_checkbox;
    public $kt_activation_tab_key;
    public $kt_deactivation_tab_key;
    public $kt_settings_menu_title;
    public $kt_settings_title;
    public $kt_menu_tab_activation_title;
    public $kt_menu_tab_deactivation_title;
    public $kt_options;
    public $kt_plugin_name;
    public $kt_product_id;
    public $kt_renew_license_url;
    public $kt_instance_id;
    public $kt_domain;
    public $kt_software_version;
    public $kt_plugin_or_theme;
    public $kt_update_version;
    public $kt_update_check = 'kt_update_check';
    public $kt_api_manager_key;
    public $kt_extra;
    protected static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function __clone() {}
    public function __wakeup() {}
    public function __construct() {

        if ( is_admin() ) {
            add_action( 'admin_notices', array( $this, 'check_external_blocking' ) );
            add_action( 'admin_init', array( $this, 'activation' ) );
            // Repeat Check license. 
            //add_filter( 'pre_set_site_transient_update_themes', array( $this, 'status_check' ) );

            $this->my_theme = wp_get_theme(); // Get theme data
            $this->version = $this->my_theme->get( 'Version' );

            /**
             * Set all data defaults here
             */
            $this->kt_data_key                      = 'kt_api_manager';
            $this->kt_api_key                       = 'kt_api_key';
            $this->kt_activation_email              = 'activation_email';
            $this->kt_product_id_key                = 'virtue_premium_api_key';
            $this->kt_instance_key                  = 'kt_api_manager_virtue_premium_instance';
            $this->kt_activated_key                 = 'kt_api_manager_virtue_premium_activated';
            $this->kt_deactivate_checkbox           = 'kt_deactivate_example_checkbox';
            $this->kt_deactivation_tab_key          = 'kt_api_manager_dashboard_deactivation';
            $this->kt_activation_tab_key            = 'kt_api_manager_dashboard';
            $this->kt_settings_menu_title           = 'Theme Activation';
            $this->kt_settings_title                = 'Virtue Premium Activation';
            $this->kt_menu_tab_activation_title     = __( 'API License Activation', 'virtue' );
            $this->kt_menu_tab_deactivation_title   = __( 'API License Deactivation', 'virtue' );
            $this->kt_options                       = get_option( $this->kt_data_key );
            $this->kt_plugin_name                   = get_stylesheet();
            $this->kt_product_id                    = 'virtue_premium'; // Software Title
            $this->kt_renew_license_url             = 'https://www.kadencethemes.com/my-account/'; // URL to renew a license
            $this->kt_instance_id                   = get_option( $this->kt_instance_key ); // Instance ID (unique to each blog activation)
            $this->kt_domain                        = preg_replace( '#(https?:)?//#','', esc_attr( untrailingslashit( network_home_url() ) ) ); // blog domain name
            $this->kt_software_version              = $this->version; // The software version
            $this->kt_plugin_or_theme               = 'theme'; // 'theme' or 'plugin'
            $this->kt_software_product_id           = $this->kt_product_id;

            require_once( 'classes/kt-key-api.php' );
            $this->kt_api_manager_key = new kt_api_manager_key();

            require_once( 'classes/kt-api-manager-menu.php' );

        }

    }
    public function theme_url() {
        if ( isset( $this->theme_url ) ) {
            return $this->theme_url;
        }
        return $this->theme_url = get_stylesheet_directory_uri() . '/';
    }

    public function activation() {
        if ( get_option( $this->kt_data_key ) === false || get_option( $this->kt_instance_key ) === false ) {
            $global_options = array(
                $this->kt_api_key           => '',
                $this->kt_activation_email  => '',
            );
            update_option( $this->kt_data_key, $global_options );
            $single_options = array(
                $this->kt_product_id_key            => $this->kt_software_product_id,
                $this->kt_instance_key              => wp_generate_password( 12, false ),
                $this->kt_deactivate_checkbox_key   => 'on',
                $this->kt_activated_key             => 'Deactivated',
                );

            foreach ( $single_options as $key => $value ) {
                update_option( $key, $value );
            }

        }

    }
    public function status_check() {
        if ( get_option( $this->kt_activated_key ) == 'Activated') {
           $data = get_option( $this->kt_data_key);
           $args = array(
                        'email'         => $data[$this->kt_activation_email],
                        'licence_key'   => $data[$this->kt_api_key],
                        );
           $status_results = json_decode( $this->kt_api_manager_key->status( $args), true );
           if($status_results['status_check'] != 'active') {
                                $this->uninstall();
                                update_option( $this->kt_activated_key, 'Deactivated' );
           }
        }
    }
    public function uninstall() {
        global $blog_id;

        $this->license_key_deactivation();

        // Remove options
        if ( is_multisite() ) {

            switch_to_blog( $blog_id );

            foreach ( array(
                    $this->kt_data_key,
                    $this->kt_product_id_key,
                    $this->kt_instance_key,
                    $this->kt_deactivate_checkbox_key,
                    $this->kt_activated_key,
                    ) as $option) {

                    delete_option( $option );

                    }

            restore_current_blog();

        } else {

            foreach ( array(
                    $this->kt_data_key,
                    $this->kt_product_id_key,
                    $this->kt_instance_key,
                    $this->kt_deactivate_checkbox_key,
                    $this->kt_activated_key
                    ) as $option) {

                    delete_option( $option );

                    }

        }

    }

    /**
     * Deactivates the license on the API server
     * @return void
     */
    public function license_key_deactivation() {

        $activation_status = get_option( $this->kt_activated_key );

        $api_email = $this->kt_options[$this->kt_activation_email];
        $api_key = $this->kt_options[$this->kt_api_key];

        $args = array(
            'email' => $api_email,
            'licence_key' => $api_key,
            );

        if ( $activation_status == 'Activated' && $api_key != '' && $api_email != '' ) {
            $this->kt_api_manager_key->deactivate( $args ); // reset license key activation
        }
    }

    /**
     * Displays an inactive notice when the software is inactive.
     */
    public static function kt_api_m_inactive_notice() { ?>
        <?php if ( ! current_user_can( 'manage_options' ) ) return; ?>
        <?php if ( isset( $_GET['page'] ) && 'api_manager_theme_example_dashboard' == $_GET['page'] ) return; ?>
        <div id="message" class="error">
            <p><?php printf( __( 'The theme update API License Key has not been activated! %sClick here%s to activate the license api key.', 'virtue' ), '<a href="' . esc_url( admin_url( 'options-general.php?page=kt_api_manager_dashboard' ) ) . '">', '</a>' ); ?></p>
        </div>
        <?php
    }

    /**
     * Check for external blocking contstant
     * @return string
     */
    public function check_external_blocking() {
        // show notice if external requests are blocked through the WP_HTTP_BLOCK_EXTERNAL constant
        if( defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL === true ) {

            // check if our API endpoint is in the allowed hosts
            $host = parse_url( $this->upgrade_url, PHP_URL_HOST );

            if( ! defined( 'WP_ACCESSIBLE_HOSTS' ) || stristr( WP_ACCESSIBLE_HOSTS, $host ) === false ) {
                ?>
                <div class="error">
                    <p><?php printf( __( '<b>Warning!</b> You\'re blocking external requests which means you won\'t be able to get %s updates. Please add %s to %s.', 'virtue' ), $this->kt_product_id, '<strong>' . $host . '</strong>', '<code>WP_ACCESSIBLE_HOSTS</code>'); ?></p>
                </div>
                <?php
            }

        }
    }

}

function kt_api_m() {
    return kt_api_manager::instance();
}

kt_api_m();

/**
 * Theme Update Checker
 */


if ( !class_exists('ThemeUpdateChecker') ):

/**
 * A custom theme update checker.
 *
 * @author Janis Elsts
 * @copyright 2012
 * @version 1.2
 * @access public
 */
class ThemeUpdateChecker {
    public $theme = '';              //The theme associated with this update checker instance.
    public $metadataUrl = '';        //The URL of the theme's metadata file.
    public $enableAutomaticChecking = true; //Enable/disable automatic update checks.
    public $purchaseCode = false;

    protected $optionName = '';      //Where to store update info.
    protected $automaticCheckDone = false;
    protected static $filterPrefix = 'tuc_request_update_';

    /**
     * Class constructor.
     *
     * @param string $theme Theme slug, e.g. "twentyten".
     * @param string $metadataUrl The URL of the theme metadata file.
     * @param boolean $enableAutomaticChecking Enable/disable automatic update checking. If set to FALSE, you'll need to explicitly call checkForUpdates() to, err, check for updates.
     */
    public function __construct($theme, $metadataUrl, $enableAutomaticChecking = true){
        $this->metadataUrl = $metadataUrl;
        $this->enableAutomaticChecking = $enableAutomaticChecking;
        $this->theme = $theme;
        $this->optionName = 'external_theme_updates-'.$this->theme;

        $this->installHooks();
    }

    /**
     * Install the hooks required to run periodic update checks and inject update info
     * into WP data structures.
     *
     * @return void
     */
    public function installHooks(){
        //Check for updates when WordPress does. We can detect when that happens by tracking
        //updates to the "update_themes" transient, which only happen in wp_update_themes().
        if ( $this->enableAutomaticChecking ){
            add_filter('pre_set_site_transient_update_themes', array($this, 'onTransientUpdate'));
        }

        //Insert our update info into the update list maintained by WP.
        add_filter('site_transient_update_themes', array($this,'injectUpdate'));

        //Delete our update info when WP deletes its own.
        //This usually happens when a theme is installed, removed or upgraded.
        add_action('delete_site_transient_update_themes', array($this, 'deleteStoredData'));
    }

    /**
     * Retrieve update info from the configured metadata URL.
     *
     * Returns either an instance of ThemeUpdate, or NULL if there is
     * no newer version available or if there's an error.
     *
     * @uses wp_remote_get()
     *
     * @param array $queryArgs Additional query arguments to append to the request. Optional.
     * @return ThemeUpdate
     */
    public function requestUpdate($queryArgs = array()){
        //Query args to append to the URL. Themes can add their own by using a filter callback (see addQueryArgFilter()).
        $queryArgs['installed_version'] = $this->getInstalledVersion();
        if($this->purchaseCode) {
            $queryArgs['code'] = $this->purchaseCode;
        }
        $queryArgs = apply_filters(self::$filterPrefix.'query_args-'.$this->theme, $queryArgs);

        //Various options for the wp_remote_get() call. Themes can filter these, too.
        $options = array(
            'timeout' => 10, //seconds
        );
        $options = apply_filters(self::$filterPrefix.'options-'.$this->theme, $options);

        $url = $this->metadataUrl;
        if ( !empty($queryArgs) ){
            $url = add_query_arg($queryArgs, $url);
        }

        //Send the request.
        $result = wp_remote_get($url, $options);

        //Try to parse the response
        $themeUpdate = null;
        $code = wp_remote_retrieve_response_code($result);
        $body = wp_remote_retrieve_body($result);
        if ( ($code == 200) && !empty($body) ){
            $themeUpdate = ThemeUpdate::fromJson($body);
            //The update should be newer than the currently installed version.
            if ( ($themeUpdate != null) && version_compare($themeUpdate->version, $this->getInstalledVersion(), '<=') ){
                $themeUpdate = null;
            }
        }

        $themeUpdate = apply_filters(self::$filterPrefix.'result-'.$this->theme, $themeUpdate, $result);
        return $themeUpdate;
    }

    /**
     * Get the currently installed version of our theme.
     *
     * @return string Version number.
     */
    public function getInstalledVersion(){
        if ( function_exists('wp_get_theme') ) {
            $theme = wp_get_theme($this->theme);
            return $theme->get('Version');
        }

        /** @noinspection PhpDeprecationInspection get_themes() used for compatibility with WP 3.3 and below. */
        foreach(get_themes() as $theme){
            if ( $theme['Stylesheet'] === $this->theme ){
                return $theme['Version'];
            }
        }
        return '';
    }

    /**
     * Check for theme updates.
     *
     * @return void
     */
    public function checkForUpdates(){
        $state = get_option($this->optionName);
        if ( empty($state) ){
            $state = new StdClass;
            $state->lastCheck = 0;
            $state->checkedVersion = '';
            $state->update = null;
        }

        $state->lastCheck = time();
        $state->checkedVersion = $this->getInstalledVersion();
        update_option($this->optionName, $state); //Save before checking in case something goes wrong

        $update = $this->requestUpdate();
        $state->update = ($update instanceof ThemeUpdate) ? $update->toJson() : $update;
        update_option($this->optionName, $state);
    }

    /**
     * Run the automatic update check, but no more than once per page load.
     * This is a callback for WP hooks. Do not call it directly.
     *
     * @param mixed $value
     * @return mixed
     */
    public function onTransientUpdate($value){
        if ( !$this->automaticCheckDone ){
            $this->checkForUpdates();
            $this->automaticCheckDone = true;
        }
        return $value;
    }

    /**
     * Insert the latest update (if any) into the update list maintained by WP.
     *
     * @param StdClass $updates Update list.
     * @return array Modified update list.
     */
    public function injectUpdate($updates){
        $state = get_option($this->optionName);

        //Is there an update to insert?
        if ( !empty($state) && isset($state->update) && !empty($state->update) ){
            $update = $state->update;
            if ( is_string($state->update) ) {
                $update = ThemeUpdate::fromJson($state->update);
            }
            $updates->response[$this->theme] = $update->toWpFormat();
        }

        return $updates;
    }

    /**
     * Delete any stored book-keeping data.
     *
     * @return void
     */
    public function deleteStoredData(){
        delete_option($this->optionName);
    }

    /**
     * Register a callback for filtering query arguments.
     *
     * The callback function should take one argument - an associative array of query arguments.
     * It should return a modified array of query arguments.
     *
     * @param callable $callback
     * @return void
     */
    public function addQueryArgFilter($callback){
        add_filter(self::$filterPrefix.'query_args-'.$this->theme, $callback);
    }

    /**
     * Register a callback for filtering arguments passed to wp_remote_get().
     *
     * The callback function should take one argument - an associative array of arguments -
     * and return a modified array or arguments. See the WP documentation on wp_remote_get()
     * for details on what arguments are available and how they work.
     *
     * @param callable $callback
     * @return void
     */
    public function addHttpRequestArgFilter($callback){
        add_filter(self::$filterPrefix.'options-'.$this->theme, $callback);
    }

    /**
     * Register a callback for filtering the theme info retrieved from the external API.
     *
     * The callback function should take two arguments. If a theme update was retrieved
     * successfully, the first argument passed will be an instance of  ThemeUpdate. Otherwise,
     * it will be NULL. The second argument will be the corresponding return value of
     * wp_remote_get (see WP docs for details).
     *
     * The callback function should return a new or modified instance of ThemeUpdate or NULL.
     *
     * @param callable $callback
     * @return void
     */
    public function addResultFilter($callback){
        add_filter(self::$filterPrefix.'result-'.$this->theme, $callback, 10, 2);
    }
}

endif;

if ( !class_exists('ThemeUpdate') ):

/**
 * A simple container class for holding information about an available update.
 *
 * @author Janis Elsts
 * @copyright 2012
 * @version 1.1
 * @access public
 */
class ThemeUpdate {
    public $version;      //Version number.
    public $details_url;  //The URL where the user can learn more about this version.
    public $download_url; //The download URL for this version of the theme. Optional.

    /**
     * Create a new instance of ThemeUpdate from its JSON-encoded representation.
     *
     * @param string $json Valid JSON string representing a theme information object.
     * @return ThemeUpdate New instance of ThemeUpdate, or NULL on error.
     */
    public static function fromJson($json){
        $apiResponse = json_decode($json);
        if ( empty($apiResponse) || !is_object($apiResponse) ){
            return null;
        }

        //Very, very basic validation.
        $valid = isset($apiResponse->version) && !empty($apiResponse->version) && isset($apiResponse->details_url) && !empty($apiResponse->details_url);
        if ( !$valid ){
            return null;
        }

        $update = new self();
        foreach(get_object_vars($apiResponse) as $key => $value){
            $update->$key = $value;
        }

        return $update;
    }

    /**
     * Serialize update information as JSON.
     *
     * @return string
     */
    public function toJson() {
        return json_encode($this);
    }

    /**
     * Transform the update into the format expected by the WordPress core.
     *
     * @return array
     */
    public function toWpFormat(){
        $update = array(
            'new_version' => $this->version,
            'url' => $this->details_url,
        );

        if ( !empty($this->download_url) ){
            $update['package'] = $this->download_url;
        }

        return $update;
    }
}

endif;