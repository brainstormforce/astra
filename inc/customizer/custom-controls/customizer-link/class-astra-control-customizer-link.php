<?php
/**
 * Customizer Control: Customizer Link
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A text control with validation for CSS units.
 */
class Astra_Control_Customizer_Link extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'ast-customizer-link';

	/**
	 * Link text to be added inside the anchor tag.
	 *
	 * @var string
	 */
	public $link_text = '';

	/**
	 * Linked customizer section.
	 *
	 * @var string
	 */
	public $linked = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();
		$this->json['link_text'] = $this->link_text;
		$this->json['linked']    = $this->linked;
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

		<# if ( data.linked && data.link_text ) { #>
			<a href="#" class="customizer-link" data-customizer-linked="{{{ data.linked }}}">
				{{{ data.link_text }}}
			</a>
		<# } #>

		<?php
	}
}
