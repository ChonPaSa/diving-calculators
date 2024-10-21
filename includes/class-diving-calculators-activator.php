<?php

/**
 * Fired during plugin activation
 *
 * @link       https://code-fish.eu
 * @since      1.0.0
 *
 * @package    Diving_Calculators
 * @subpackage Diving_Calculators/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Diving_Calculators
 * @subpackage Diving_Calculators/includes
 * @author     Choni code-fish <choni@code-fish.eu>
 */
class Diving_Calculators_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		//Create option with the activation time
		$get_activation_time = strtotime("now");
		add_option('diving_calculators_activation_time', $get_activation_time );  

	}

}
