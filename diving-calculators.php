<?php

/**
 *
 * @link              https://code-fish.eu
 * @since             1.1.0
 * @package           Diving_Calculators
 *
 * @wordpress-plugin
 * Plugin Name:       Diving Calculators
 * Plugin URI:        https://code-fish.eu/diving-plugins/
 * Description:       Diving Calculators Widget.
 * Version:           1.0.0
 * Author:            Choni code-fish
 * Author URI:        https://code-fish.eu
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       diving-calculators
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DIVING_CALCULATORS_VERSION', '1.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-diving-calculators-activator.php
 */
function activate_diving_calculators() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-diving-calculators-activator.php';
	Diving_Calculators_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-diving-calculators-deactivator.php
 */
function deactivate_diving_calculators() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-diving-calculators-deactivator.php';
	Diving_Calculators_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_diving_calculators' );
register_deactivation_hook( __FILE__, 'deactivate_diving_calculators' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-diving-calculators.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_diving_calculators() {

	$plugin = new Diving_Calculators();
	$plugin->run();

}
run_diving_calculators();
