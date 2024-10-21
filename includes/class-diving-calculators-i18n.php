<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://code-fish.eu
 * @since      1.0.0
 *
 * @package    Diving_Calculators
 * @subpackage Diving_Calculators/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Diving_Calculators
 * @subpackage Diving_Calculators/includes
 * @author     Choni code-fish <choni@code-fish.eu>
 */
class Diving_Calculators_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'diving-calculators',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
