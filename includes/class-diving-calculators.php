<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://code-fish.eu
 * @since      1.0.0
 *
 * @package    Diving_Calculators
 * @subpackage Diving_Calculators/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Diving_Calculators
 * @subpackage Diving_Calculators/includes
 * @author     Choni code-fish <choni@code-fish.eu>
 */
class Diving_Calculators {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Diving_Calculators_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'DIVING_CALCULATORS_VERSION' ) ) {
			$this->version = DIVING_CALCULATORS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'diving-calculators';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_public_hooks();

		$this->check_installation_time();


	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Diving_Calculators_Loader. Orchestrates the hooks of the plugin.
	 * - Diving_Calculators_i18n. Defines internationalization functionality.
	 * - Diving_Calculators_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-diving-calculators-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-diving-calculators-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-diving-calculators-public.php';

		/* widgets include */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/widgets.php';

		$this->loader = new Diving_Calculators_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Diving_Calculators_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Diving_Calculators_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Diving_Calculators_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );


		$this->loader->add_action( 'wp_ajax_form_calc', $plugin_public, 'form_calc' );
		$this->loader->add_action( 'wp_ajax_nopriv_form_calc', $plugin_public, 'form_calc' );

	}

	/**
	 * Checkk the option saved when the plugin was activated
	 *
	 * @since    1.1.0
	 * @access   public
	 */
	public function check_installation_time() {
        $install_date = get_option( 'diving_calculators_activation_time' );
        
		if($install_date){
			//$past_date = strtotime( '-7 days' );
			$past_date = strtotime( '-1 days' );
		
			if ( $past_date >= $install_date ) {
		
			add_action( 'admin_notices', array($this, 'display_admin_notice') );
			}
		}

		add_action( 'admin_init', array( $this, 'dismiss_notice'), 5 );

		add_action( 'admin_init', array( $this, 'delay_notice'), 5 );
	}

	/**
	 * Display admin notice for review
	 *
	 * @since    1.1.0
	 * @access   public
	 */
	public function display_admin_notice() {

		global $pagenow;

		if( $pagenow == 'index.php' ){
	
			$dismiss = esc_url( get_admin_url() . 'index.php?dismiss=1' );

			$delay = esc_url( get_admin_url() . 'index.php?delay=1' );
  
			$reviewurl = esc_url( 'https://wordpress.org/support/plugin/'. sanitize_title( $this->get_plugin_name() ) . '/reviews/' );
		
			printf(__('<div class="update-nag notice notice-info">
							<h3>Diving Calculators</h3>
							<p>Hey, I noticed you’ve been using <strong>Diving Calculators</strong> for a while – that’s awesome! 
							Could you please do me a BIG favor and give it a 5-star rating on WordPress?</p>
							<div>
								<a href="%s" class="button button-primary" target="_blank">Ok, you deserve it</a>
								<a href="%s" class="button button-secondary">Nope, maybe later</a>
								<a class="button button-secondary" href="%s" >I already did</a>
							</div>
						</div>', $this->get_plugin_name()),$reviewurl, $delay, $dismiss );
		}
	}


	/**
	 * Remove the notice for the user if review already done 
	 *
	 * @since    1.1.0
	 * @access   public
	 */
	function dismiss_notice(){    
		if( isset( $_GET['dismiss'] ) && !empty( $_GET['dismiss'] ) ){
			$dismiss = $_GET['dismiss'];
			if( $dismiss == 1 ){
				delete_option ('diving_calculators_activation_time');
				header("Refresh:0; url=index.php");
			}
		}
	}

	/**
	 * Change time in setting to remind user later (7days)
	 *
	 * @since    1.1.0
	 * @access   public
	 */
	function delay_notice(){    
		if( isset( $_GET['delay'] ) && !empty( $_GET['delay'] ) ){
			$delay = $_GET['delay'];
			if( $delay == 1 ){
				$get_activation_time = strtotime("now");
				update_option('diving_calculators_activation_time', $get_activation_time );  
				header("Refresh:0; url=index.php");
			}
		}
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Diving_Calculators_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
