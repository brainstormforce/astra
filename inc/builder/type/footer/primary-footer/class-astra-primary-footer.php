<?php
/**
 * Primary Footer component.
 *
 * @package     Astra Builder
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ASTRA_BUILDER_FOOTER_PRIMARY_FOOTER_DIR', ASTRA_THEME_DIR . 'inc/builder/type/footer/primary-footer' );
define( 'ASTRA_BUILDER_FOOTER_PRIMARY_FOOTER_URI', ASTRA_THEME_URI . 'inc/builder/type/footer/primary-footer' );

if ( ! class_exists( 'Astra_Primary_Footer' ) ) {

	/**
	 * Primary Footer Initial Setup
	 *
	 * @since 3.0.0
	 */
	class Astra_Primary_Footer {

		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once ASTRA_BUILDER_FOOTER_PRIMARY_FOOTER_DIR . '/class-astra-primary-footer-component-loader.php';

			// Include front end files.
			if ( ! is_admin() ) {

				require_once ASTRA_BUILDER_FOOTER_PRIMARY_FOOTER_DIR . '/dynamic-css/dynamic.css.php';
			}
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}

	/**
	 *  Kicking this off by creating an object.
	 */
	new Astra_Primary_Footer();

}
