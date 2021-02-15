<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://code-fish.eu
 * @since      1.0.0
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

		$sanitize_form = array();
		for ( $i=0; $i < count($_POST['formData']); $i++){
			if ($_POST['formData'][$i]['value']){
				$sanitize_form[$i] = sanitize_text_field( $_POST['formData'][$i]['value']);
			}
			if ($i > 2){
				$sanitize_form[2] = is_numeric($sanitize_form[$i])?$sanitize_form[2]: 'error';
			}
	
		}
		
		if ($sanitize_form[0]== 'metric'){
			$unit = 10;
			$unit_name = ' meters';
			$unit_volume = ' liters';
			$unit_weight = ' kg';
		}
		if ($sanitize_form[0]== 'imperial'){
			$unit = 32.8084;
			$unit_name = ' feet';
			$unit_volume = ' cu foot';
			$unit_weight = ' lbs';
		}
		
		$water = ($sanitize_form[1]== 'fresh')? 1.02 : 1;

		switch($sanitize_form[2] ){
			case 'mod':
				$value = ($sanitize_form[4] / $sanitize_form[3]-1) * $unit * $water;
				$value = number_format((float)($value),2);
				$result = esc_attr(__('Maximum Operating Depth is ','diving-calculators').$value.$unit_name);
				break;

			case 'bnm':
				$value = $sanitize_form[3] /(($sanitize_form[4] / $unit ) +1) * $water;
				$value =number_format($value*100);
				$result = esc_attr(__('The best Nitrox mix is ','diving-calculators').$value.'%');
				break;
				
			case 'ead':
				$value = (($sanitize_form[4]+ $unit ) * ((1 - floatval($sanitize_form[3])) / 0.79) )- 10;
				$value = number_format((float)($value),2);
				$result = esc_attr(__('Equivalent Air Depth is ','diving-calculators').$value.$unit_name);
				break;

			case 'end':
				$value = (($sanitize_form[4]+ $unit ) * (1 - floatval($sanitize_form[3]))- 10) * $water;
				$value = number_format((float)($value),2);
				$result = esc_attr(__('Equivalent Narcotic Depth is ','diving-calculators').$value.$unit_name);
				break;

			case 'sac':
				$gas = (($sanitize_form[0]== 'imperial')? ($sanitize_form[5]/3000 ): ($sanitize_form[5]));
				$value = (($gas * $sanitize_form[6]) / $sanitize_form[3]) / (1 +($sanitize_form[4] / $unit));
				$value = number_format((float)($value),2);
				$result = esc_attr(__('Surface Air Consumption Rate is ','diving-calculators').$value.$unit_volume. '/min');
				break;

			case 'alt':
				$c_pressure = ($unit==10) ? 0.000022558 : 0.0000068756;
				$pressure = exp(5.255876 * log(1 - ($c_pressure * $sanitize_form[3])));
				$pressure = number_format((float)($pressure),3);
				$value = $sanitize_form[4] * (1 / $pressure) * (33/34);
				$value = number_format((float)($value),2);
				$result = esc_attr(__('The surface air pressure is ','diving-calculators').$pressure.' atm. ');
				$result .= esc_attr(__('Theoretical Ocean Depth is ','diving-calculators').$value.$unit_name);
				break;

			case 'lbv':

				$water_weight = (($sanitize_form[1]== 'salt')? ($sanitize_form[4]*(34/33)): ($sanitize_form[4]));
				$displacement_kg = number_format((float)($sanitize_form[3] - $water_weight),2);
				$displacement_lt = $displacement_kg / ($sanitize_form[1]== 'salt'? (34/33): 1);
				$displacement_lt = number_format((float)($displacement_lt),2);
				$air = $displacement_lt * (1 + ($sanitize_form[5] /10 ));
				$air = number_format((float)($air),2);
				if($sanitize_form[0]== 'metric'){
					$result = esc_attr(__('You need a lift capacity of ','diving-calculators').$displacement_kg.$unit_weight.' ('.$displacement_lt.$unit_volume.').');
					$result .= esc_attr(__(' The lift bag uses ', 'diving-calculators').$air.$unit_volume. __(' of air', 'diving-calculators'));
				}
				else{
					$displacement_lbs = number_format((float)($displacement_kg * 2.20462),2);
					$displacement_cufoot = number_format((float)($displacement_lt /  28.317),2);
					$air_imperial = number_format((float)($air / 28.317),2);
					$result = esc_attr(__('You need a lift capacity of ','diving-calculators').$displacement_lbs.$unit_weight.' ('.$displacement_cufoot.$unit_volume.').');
					$result .= esc_attr(__(' The lift bag uses ', 'diving-calculators').$air_imperial.$unit_volume. __(' of air', 'diving-calculators'));	
				}
				break;

			case 'error':
				$result = esc_attr(__('Form data is not valid. Insert only numbers','diving-calculators'));
				break;	

			default:
				$result = 0;
		}

		exit($result);
	}

}
