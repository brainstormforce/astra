<?php
/**
 * Customizer Control: divider
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        https://wpastra.com/
 * @since       2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A text control with validation for CSS units.
 */
class Astra_Control_Hidden extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @since  2.0.0
	 * @var string
	 */
	public $type = 'ast-hidden';


	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 * @since  2.0.0
	 */
	public function to_json() {
		parent::to_json();
		$this->json['default'] = $this->setting->default;
		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}
		$this->json['value'] = $this->value();
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 * @since  2.0.0
	 */
	protected function content_template() {
		?>
		<#
		var name = data.settings.default;
		name = name.replace( '[', '-' );
		name = name.replace( ']', '' );
		#>
		<input type='hidden' class='hidden-field-{{name}}' data-name='{{name}}' value='{{data.value}}'>
		<?php
	}

	/**
	 * Render the control's content.
	 *
	 * @see WP_Customize_Control::render_content()
	 * @since  2.0.0
	 */
	protected function render_content() {}
}
