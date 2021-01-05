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


define( 'ASTRA_HEADER_BUTTON_DIR', ASTRA_THEME_DIR . 'inc/builder/type/header/button' );
define( 'ASTRA_HEADER_BUTTON_URI', ASTRA_THEME_URI . 'inc/builder/type/header/button' );

/**
 * Heading Initial Setup
 *
 * @since 2.1.4
 */
class Astra_Header_Button_Component {

	/**
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once ASTRA_HEADER_BUTTON_DIR . '/class-astra-header-button-component-loader.php';

		// Include front end files.
		if ( ! is_admin() ) {
			require_once ASTRA_HEADER_BUTTON_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

}

/**
 *  Kicking this off by creating an object.
 */
new Astra_Header_Button_Component();
