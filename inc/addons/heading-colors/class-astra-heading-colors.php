<?php
/**
 * Heading Colors for Astra theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 2.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ASTRA_THEME_HEADING_COLORS_DIR', ASTRA_THEME_DIR . 'inc/addons/heading-colors/' );
define( 'ASTRA_THEME_HEADING_COLORS_URI', ASTRA_THEME_URI . 'inc/addons/heading-colors/' );

if ( ! class_exists( 'Astra_Heading_Colors' ) ) {

	/**
	 * Heading Initial Setup
	 *
	 * @since 2.1.4
	 */
	class Astra_Heading_Colors {

		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			require_once ASTRA_THEME_HEADING_COLORS_DIR . 'class-astra-heading-colors-loader.php';

			// Include front end files.
			if ( ! is_admin() ) {
				require_once ASTRA_THEME_HEADING_COLORS_DIR . 'dynamic-css/dynamic.css.php';
			}
		}
	}

	/**
	 *  Kicking this off by creating an object.
	 */
	new Astra_Heading_Colors();

}
