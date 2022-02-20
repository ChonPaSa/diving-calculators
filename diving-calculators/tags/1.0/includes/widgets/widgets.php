<?php
/**
 * Diving Calculators Widget
 *
 * @package Diving_Calculators
 */


class DivingCalculators extends WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	function __construct() {

		// Set up defaults.
		$this->defaults = array(
			'title'        => esc_attr__( 'Scuba Diving Calculators', 'diving-calculators' ),
			'accordion'	  => esc_attr__ ('Collapse the calculators', 'diving-calculators' ),
			'mod'         => esc_attr__( 'Maximum Operation Depth', 'diving-calculators' ),
			'bnm'         => esc_attr__( 'Best Nitrox Mix', 'diving-calculators' ),
			'ead'         => esc_attr__( 'Equivalent Air Depth', 'diving-calculators' ),
			'end'         => esc_attr__( 'Equivalent Narcotic Depth', 'diving-calculators' ),
			'sac'         => esc_attr__( 'Surface Air Consumption Rate', 'diving-calculators' ),
			'alt'         => esc_attr__( 'Altitude Diving', 'diving-calculators' ),
			'lbv'         => esc_attr__( 'Lift Bag Volume', 'diving-calculators' ),

		);

		$widget_ops = array(
			'classname'   => 'diving-calculators',
			'description' => esc_html__( 'Diving Calculators', 'diving-calculators' ),
		);

		$control_ops = array(
			'id_base' => 'dc'
		);

		parent::__construct(
			'dc', // Base ID
			__( 'Diving Calculators', 'diving-calculators' ), // Name
			$widget_ops,
			$control_ops
		);
	}

	function form($instance) {
		// Merge the user-selected arguments with the defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );
 ?>

		<p>
			<label>
				<?php esc_html_e( 'Calculators Title:', 'diving-calculators' ); ?>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" placeholder="<?php echo esc_attr( $this->defaults['title'] ); ?>" />
			</label>
		</p>
		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['accordion'], true ); ?> name="<?php echo esc_attr( $this->get_field_name( 'accordion' ) ); ?>" />
				<?php esc_html_e( 'Collapse the calculators', 'essential-widgets' ); ?>
			</label>
		</p>
		<p>
			<?php esc_html_e( 'Choose the calculators:', 'diving-calculators' ); ?>	
		</p>
		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['mod'], true ); ?> name="<?php echo esc_attr( $this->get_field_name( 'mod' ) ); ?>" />
				<?php esc_html_e( 'Maximum Operation Depth', 'essential-widgets' ); ?>
			</label>
		</p>
		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['bnm'], true ); ?> name="<?php echo esc_attr( $this->get_field_name( 'bnm' ) ); ?>" />
				<?php esc_html_e( 'Best Nitrox Mix', 'essential-widgets' ); ?>
			</label>
		</p>
		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['ead'], true ); ?> name="<?php echo esc_attr( $this->get_field_name( 'ead' ) ); ?>" />
				<?php esc_html_e( 'Equivalent Air Depth', 'essential-widgets' ); ?>
			</label>
		</p>
		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['end'], true ); ?> name="<?php echo esc_attr( $this->get_field_name( 'end' ) ); ?>" />
				<?php esc_html_e( 'Equivalent Narcotic Depth', 'essential-widgets' ); ?>
			</label>
		</p>
		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['sac'], true ); ?> name="<?php echo esc_attr( $this->get_field_name( 'sac' ) ); ?>" />
				<?php esc_html_e( 'Surface Air Consumption Rate', 'essential-widgets' ); ?>
			</label>
		</p>
		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['alt'], true ); ?> name="<?php echo esc_attr( $this->get_field_name( 'alt' ) ); ?>" />
				<?php esc_html_e( 'Altitude Diving', 'essential-widgets' ); ?>
			</label>
		</p>
		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['lbv'], true ); ?> name="<?php echo esc_attr( $this->get_field_name( 'lbv' ) ); ?>" />
				<?php esc_html_e( 'Lift Bag Volume', 'essential-widgets' ); ?>
			</label>
		</p>

		<div style="clear:both;">&nbsp;</div>
		<?php
	}

	function update( $new_instance, $old_instance ) {

		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['accordion'] = $new_instance['accordion']? 1 : 0;
		$instance['mod'] = $new_instance['mod']? 1 : 0;
		$instance['bnm'] = $new_instance['bnm']? 1 : 0;
		$instance['ead'] = $new_instance['ead']? 1 : 0;
		$instance['end'] = $new_instance['end']? 1 : 0;
		$instance['sac'] = $new_instance['sac']? 1 : 0;
		$instance['alt'] = $new_instance['alt']? 1 : 0;
		$instance['lbv'] = $new_instance['lbv']? 1 : 0;

		
		// Return sanitized options.
		return $instance;
	}

	function widget( $args, $instance ) {
		// Set the $args to the $instance array.
		$instance = wp_parse_args( $instance, $this->defaults );

		// instance the $echo argument and set it to false.
		$instance['echo'] = false;

		// Output the args's $before_widget wrapper.
		echo $args['before_widget'];

		$collap_open = '<div class="dc-collapsible">';
		$collap_close = '</div><div class=dc-content>';
		$collap_close_div = '</div>';


		// If a title was input by the user, display it.
		if ( ! empty( $instance['title'] ) )
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];

		?>
			<table cellspacing="0" cellpadding="0" class="diving_calculator_options">
				<tr>
					<td><input type="radio" id="metric" name="diving_calc_system" value="metric" checked>
					<label for="metric">Metric </label></td>
					<td><input type="radio" id="imperial" name="diving_calc_system" value="imperial">
					<label for="imperial">Imperial</label></td>
				</tr>
				<tr>
					<td><input type="radio" id="salt" name="diving_calc_water" value="salt" checked>
					<label for="salt">Salt </label></td>
					<td><input type="radio" id="fresh" name="diving_calc_water" value="fresh">
					<label for="fresh">Fresh</label></td>
				</tr>
			</table>
			<p>** This will affect all calculations</p>
		<?php
			if ( $instance['mod']){
				?>
				<form action="" method="post" class="diving-calculators-form">
					<input id="diving_calc_type" name="diving_calc_type" value="mod" type="hidden">
					<?php echo ($instance['accordion'] ? $collap_open : null);?>
						<h4 class="widget-title">Max. Operation Depth</h4>
					<?php echo ($instance['accordion'])? $collap_close: ''; ?>
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td><label for="calc_fo2">Fraction of Oxygen (FO<sub>2</sub>)</label></td>
							<td><input id="calc_fo2" name="calc_fo2" maxlength="256" size="5"  tabindex="1" value="0.21" type="text" required="required"><br>
							<small>0.32 (= 32%)</small></td>
						</tr>	
						<tr>
							<td><label for="calc_ppo2">Partial Pressure Oxygen (ppO<sub>2</sub>)</label></td>
							<td><input id="calc_ppo2" name="calc_ppo2" maxlength="256" size="5" tabindex="2" value="1.4" type="text" required="required"><br>
							<small>1.2 to 1.6 bar</small></td>
						</tr>
					</table>	
					<p id="diving-calculators-mod-result"> </p>
					<input type="submit" value="Calculate" class="btn">
					<?php echo ($instance['accordion'] ? $collap_close_div: null);?>
				</form>
				<?php
			}
			if ( $instance['bnm']){
				?>
				<form action="" method="post" class="diving-calculators-form">
					<input id="diving_calc_type" name="diving_calc_type" value="bnm" type="hidden">
					<?php echo ($instance['accordion'] ? $collap_open : null);?>
						<h4 class="widget-title">Best Nitrox Mix</h4>
					<?php echo ($instance['accordion'])? $collap_close: ''; ?>
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td><label for="calc_ppo2">Maximum Partial Pressure Oxygen (ppO<sub>2</sub>)</label></td>
							<td><input id="calc_ppo2" name="calc_ppo2" maxlength="256" size="5" tabindex="2" value="1.4" type="text" required="required"><br>
							<small>1.2 to 1.6 bar</small></td>
						</tr>	
						<tr>
							<td><label for="calc_depth">Maximum Depth</label></td>
							<td><input id="calc_depth" name="calc_depth" maxlength="256" size="5"  tabindex="1" value="35" type="text" required="required"><br>
							<small>meters/ feet</small></td>
						</tr>
					</table>	
					<p id="diving-calculators-bnm-result"> </p>
					<input type="submit" value="Calculate" class="btn">
					<?php echo ($instance['accordion'] ? $collap_close_div: null);?>
				</form>
				<?php
			}
			if ( $instance['ead']){
				?>
				<form action="" method="post" class="diving-calculators-form">
						<input id="diving_calc_type" name="diving_calc_type" value="ead" type="hidden">
						<?php echo ($instance['accordion'] ? $collap_open : null);?>
							<h4 class="widget-title">Equivalent Air Depth</h4>
						<?php echo ($instance['accordion'])? $collap_close: ''; ?>
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td><label for="calc_fo2">Fraction of Oxygen (FO<sub>2</sub>)</label></td>
							<td><input id="calc_fo2" name="calc_fo2" maxlength="256" size="5"  tabindex="1" value="0.21" type="text" required="required"><br>
							<small>0.32 (= 32%)</small></td>
						</tr>	
							<td><label for="calc_depth">Planned Depth</label></td>
							<td><input id="calc_depth" name="calc_depth" maxlength="256" size="5" tabindex="2" value="35" type="text" required="required"><br>
							<small>meters/ feet</small></td>
						</tr>
					</table>	
					<p id="diving-calculators-ead-result"> </p>
					<input type="submit" value="Calculate" class="btn">
					<?php echo ($instance['accordion'] ? $collap_close_div: null);?>
				</form>
				<?php
			}
			if ( $instance['end']){
				?>
				<form action="" method="post" class="diving-calculators-form">
					<input id="diving_calc_type" name="diving_calc_type" value="end" type="hidden">
					<?php echo ($instance['accordion'] ? $collap_open : null);?>
						<h4 class="widget-title">Equivalent Narcotic Depth</h4>
					<?php echo ($instance['accordion'])? $collap_close: ''; ?>
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td><label for="calc_fhe">Fraction of Helium (FHe)</label></td>
							<td><input id="calc_fhe" name="calc_fhe" maxlength="256" size="5"  tabindex="1" value="0.4" type="text" required="required"><br>
							<small>0.4 (= 40%)</small></td>
						</tr>	
							<td><label for="calc_depth">Planned Depth</label></td>
							<td><input id="calc_depth" name="calc_depth" maxlength="256" size="5" tabindex="2" value="70" type="text" required="required"><br>
							<small>meters/ feet</small></td>
						</tr>
					</table>	
					<p id="diving-calculators-end-result"> </p>
					<input type="submit" value="Calculate" class="btn">
					<?php echo ($instance['accordion'] ? $collap_close_div: null);?>
				</form>
				<?php
			}
			if ( $instance['sac']){
				?>
				<form action="" method="post" class="diving-calculators-form">
					<input id="diving_calc_type" name="diving_calc_type" value="sac" type="hidden">
					<?php echo ($instance['accordion'] ? $collap_open : null);?>
						<h4 class="widget-title">Surface Air Consumption</h4>
					<?php echo ($instance['accordion'])? $collap_close: ''; ?>
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td><label for="calc_time">Time at Depth</label></td>
							<td><input id="calc_time" name="calc_time" maxlength="256" size="5"  tabindex="1" value="45" type="text" required="required"><br>
							<small>minutes</small></td>
						</tr>	
							<td><label for="calc_depth">Average Depth</label></td>
							<td><input id="calc_depth" name="calc_depth" maxlength="256" size="5" tabindex="2" value="25" type="text" required="required"><br>
							<small>meters / feet </small></td>
						</tr>
						</tr>	
							<td><label for="calc_gas">Gas Used</label></td>
							<td><input id="calc_gas" name="calc_gas" maxlength="256" size="5" tabindex="2" value="150" type="text" required="required"><br>
							<small>150bar / 2000psi</small></td>
						</tr>
						</tr>	
							<td><label for="calc_size">Cylinder Size</label></td>
							<td><input id="calc_size" name="calc_size" maxlength="256" size="5" tabindex="2" value="12.2" type="text" required="required"><br>
							<small>12L /80 cu foot</small></td>
						</tr>
					</table>	
					<p id="diving-calculators-sac-result"> </p>
					<input type="submit" value="Calculate" class="btn">
					<?php echo ($instance['accordion'] ? $collap_close_div: null);?>
				</form>
				<?php
			}
			if ( $instance['alt']){
				?>
				<form action="" method="post" class="diving-calculators-form">
					<input id="diving_calc_type" name="diving_calc_type" value="alt" type="hidden">
					<?php echo ($instance['accordion'] ? $collap_open : null);?>
						<h4 class="widget-title">Altitude Diving</h4>
					<?php echo ($instance['accordion'])? $collap_close: ''; ?>
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td><label for="calc_alt">Altitude</label></td>
							<td><input id="calc_alt" name="calc_alt" maxlength="256" size="5"  tabindex="1" value="1000" type="text" required="required"><br>
							<small>meters/ feet</small></td>
						</tr>	
							<td><label for="calc_depth">Actual Depth</label></td>
							<td><input id="calc_depth" name="calc_depth" maxlength="256" size="5" tabindex="2" value="25" type="text" required="required"><br>
							<small>meters/ feet</small></td>
						</tr>
					</table>	
					<p id="diving-calculators-alt-result"> </p>
					<input type="submit" value="Calculate" class="btn">
					<?php echo ($instance['accordion'] ? $collap_close_div: null);?>
				</form>
				<?php
			}
			if ( $instance['lbv']){
				?>
				<form action="" method="post" class="diving-calculators-form">
					<input id="diving_calc_type" name="diving_calc_type" value="lbv" type="hidden">
					<?php echo ($instance['accordion'] ? $collap_open : null);?>
						<h4 class="widget-title">Lift Bag Volume</h4>
					<?php echo ($instance['accordion'])? $collap_close: ''; ?>
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td><label for="calc_weight">Object Weight</label></td>
							<td><input id="calc_weight" name="calc_weight" maxlength="256" size="5"  tabindex="1" value="250" type="text" required="required"><br>
							<small>kg / lbs</small></td>
						</tr>	
							<td><label for="calc_vol">Object Volumen</label></td>
							<td><input id="calc_vol" name="calc_vol" maxlength="256" size="5" tabindex="2" value="100" type="text" required="required"><br>
							<small>liters / cu foot</small></td>
						</tr>
						</tr>	
							<td><label for="calc_depth">Depth</label></td>
							<td><input id="calc_depth" name="calc_depth" maxlength="256" size="5" tabindex="2" value="35" type="text" required="required"><br>
							<small>meters / feet</small></td>
						</tr>
					</table>	
					<p id="diving-calculators-lbv-result"> </p>
					<input type="submit" value="Calculate" class="btn">
					<?php echo ($instance['accordion'] ? $collap_close_div: null);?>
				</form>
				<?php
			}
		// Close the args's widget wrapper.
		echo $args['after_widget'];
	}
}// end DivingCalculators class

/**
 * Intiate DivingCalculators Class.
 *
 * @since 1.0.0
 */
function diving_calculators_register() {
	register_widget( 'DivingCalculators' );
}
add_action( 'widgets_init', 'diving_calculators_register' );
