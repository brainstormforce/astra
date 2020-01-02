<?php
/**
 * Customizer Control: Heading
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       1.4.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A text control with validation for CSS units.
 */
class Astra_Control_Heading extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'ast-heading';

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $caption = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();
		$this->json['label']       = esc_html( $this->label );
		$this->json['caption']     = $this->caption;
		$this->json['description'] = $this->description;
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
	 */
	protected function content_template() {
		?>

		<# if ( data.caption ) { #>
			<span class="customize-control-caption">{{{ data.caption }}}</span>
		<# } #>
		<div class="ast-heading-wrapper wp-ui-highlight">
			<label class="customizer-text">
				<# if ( data.label ) { #>
					<span class="customize-control-title wp-ui-text-highlight">{{{ data.label }}}</span>
				<# } #>
				<# if ( data.description ) { #>
					<span class="description customize-control-description">{{{ data.description }}}</span>
				<# } #>
			</label>
		</div>
		<?php
	}
}

