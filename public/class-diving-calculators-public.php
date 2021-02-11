<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://code-fish.eu
 * @since      1.0.0
 *
 * @package    Diving_Calculators
 * @subpackage Diving_Calculators/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Diving_Calculators
 * @subpackage Diving_Calculators/public
 * @author     Choni code-fish <choni@code-fish.eu>
 */
class Diving_Calculators_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Diving_Calculators_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Diving_Calculators_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/diving-calculators-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Diving_Calculators_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Diving_Calculators_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/diving-calculators-public.js', array( 'jquery' ), $this->version, false );

		wp_localize_script( $this->plugin_name, 'diving_calculators_ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}


	/**
	 * Calculate the values based on the type of calculator
	 *
	 * @since    1.0.0
	 */
	public function form_calc() {

		if ($_POST['formData'][0]['value']== 'metric'){
			$unit = 10;
			$unit_name = ' meters';
			$unit_volume = ' liters';
		}
		if ($_POST['formData'][0]['value']== 'imperial'){
			$unit = 32.8084;
			$unit_name = ' feet';
			$unit_volume = ' cu feet';
		}
		
		$water = ($_POST['formData'][1]['value']== 'fresh')? 1.02 : 1;

		switch($_POST['formData'][2]['value'] ){
			case 'mod':
				$value = ($_POST['formData'][4]['value'] / $_POST['formData'][3]['value']-1) * $unit * $water;
				$value = number_format((float)($value),2);
				$result = __('Maximum Operating Depth is ','diving-calculators').$value.$unit_name;
				break;

			case 'bnm':
				$value = $_POST['formData'][3]['value'] /(($_POST['formData'][4]['value'] / $unit ) +1) * $water;
				$value =number_format($value*100);
				$result = __('The best Nitrox mix is ','diving-calculators').$value.'%';
				break;
				
			case 'ead':
				$value = (($_POST['formData'][4]['value']+ $unit ) * ((1 - floatval($_POST['formData'][3]['value'])) / 0.79) )- 10;
				$value = number_format((float)($value),2);
				$result = __('Equivalent Air Depth is ','diving-calculators').$value.$unit_name;
				break;

			case 'end':
				$value = (($_POST['formData'][4]['value']+ $unit ) * (1 - floatval($_POST['formData'][3]['value']))- 10) * $water;
				$value = number_format((float)($value),2);
				$result = __('Equivalent Narcotic Depth is ','diving-calculators').$value.$unit_name;
				break;

			case 'sac':
				$gas = (($_POST['formData'][0]['value']== 'imperial')? ($_POST['formData'][5]['value']/3000 ): ($_POST['formData'][5]['value']));
				$value = (($gas * $_POST['formData'][6]['value']) / $_POST['formData'][3]['value']) / (1 +($_POST['formData'][4]['value'] / $unit));
				$value = number_format((float)($value),2);
				$result = __('Surface Air Consumption Rate is ','diving-calculators').$value.$unit_volume. '/min';
				break;

			case 'alt':
				$c_pressure = ($unit==10) ? 0.000022558 : 0.0000068756;
				$pressure = exp(5.255876 * log(1 - ($c_pressure * $_POST['formData'][3]['value'])));
				$pressure = number_format((float)($pressure),3);
				$value = $_POST['formData'][4]['value'] * (1 / $pressure) * (33/34);
				$value = number_format((float)($value),2);
				$result = __('The surface air pressure is ','diving-calculators').$pressure.' atm. ';
				$result .= __('Theoretical Ocean Depth is ','diving-calculators').$value.$unit_name;
				break;

			case 'lbv':
				$displacement = (($_POST['formData'][1]['value']== 'salt')? ($_POST['formData'][4]['value']* 1.03): ($_POST['formData'][4]));
				$bouyancy = ($displacement - $_POST['formData'][3]['value']);
				echo "displacement $displacement";
				echo "bouyancy $bouyancy";
				$air = $_POST['formData'][3]['value'] / (($_POST['formData'][1]['value']== 'salt')? 1.03 : 1)- $_POST['formData'][4]['value'];
				$value = ($_POST['formData'][4]['value'] / $_POST['formData'][3]['value']-1) * $unit * $water;
				$value = number_format((float)($value),2);
				$result = __('You need a lift capacity of ','diving-calculators').$value.$unit_name. '. The lift bag uses '.$air. 'L of air';
				break;

			default:
				$result = 0;
		}

		exit($result);
	}

}
