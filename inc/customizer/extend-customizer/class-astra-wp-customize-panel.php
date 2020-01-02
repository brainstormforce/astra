<?php
/**
 * Customizer Control: panel.
 *
 * Creates a jQuery color control.
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

if ( class_exists( 'WP_Customize_Panel' ) ) {

	/**
	 * Adds a custom Panel for nested panels / sections.
	 *
	 * @link https://gist.github.com/OriginalEXE/9a6183e09f4cae2f30b006232bb154af
	 * @since 1.0.31
	 * @see WP_Customize_Panel
	 */
	class Astra_WP_Customize_Panel extends WP_Customize_Panel {

		/**
		 * Panel
		 *
		 * @since 1.0.31
		 * @var string
		 */
		public $panel;

		/**
		 * Control type.
		 *
		 * @since  1.0.31
		 * @var string
		 */
		public $type = 'ast_panel';

		/**
		 * Get section parameters for JS.
		 *
		 * @since 1.0.31
		 * @return array Exported parameters.
		 */
		public function json() {

			$array                   = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'type', 'panel' ) );
			$array['title']          = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
			$array['content']        = $this->get_content();
			$array['active']         = $this->active();
			$array['instanceNumber'] = $this->instance_number;

			return $array;
		}
	}

}

