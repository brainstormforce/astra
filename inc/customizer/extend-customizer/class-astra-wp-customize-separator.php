<?php
/**
 * Customizer Control: panel.
 *
 * Creates a jQuery color control.
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

if ( class_exists( 'WP_Customize_Section' ) ) {

	/**
	 * Adds a custom Customize Section for section separator.
	 *
	 * @link https://gist.github.com/OriginalEXE/9a6183e09f4cae2f30b006232bb154af
	 * @since 2.0.0
	 * @see WP_Customize_Section
	 */
	class Astra_WP_Customize_Separator extends WP_Customize_Section {

		/**
		 * Control type.
		 *
		 * @since  1.0.31
		 * @var string
		 */
		public $type = 'ast-section-separator';

		/**
		 * Template for section separator
		 *
		 * @since 2.0.0
		 */
		protected function render_template() {
			?>
			<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }}"></li>
			<?php
		}
	}
}
