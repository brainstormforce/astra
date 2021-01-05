<?php
/**
 * Below Header.
 *
 * @package     astra-builder
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ASTRA_BELOW_HEADER_DIR', ASTRA_THEME_DIR . 'inc/builder/type/header/below-header' );
define( 'ASTRA_BELOW_HEADER_URI', ASTRA_THEME_URI . 'inc/builder/type/header/below-header' );

/**
 * Below Header Initial Setup
 *
 * @since 3.0.0
 */
class Astra_Below_Header {

	/**
	 * Constructor function that initializes required actions and hooks.
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once ASTRA_BELOW_HEADER_DIR . '/class-astra-below-header-loader.php';

		// Include front end files.
		if ( ! is_admin() ) {
			require_once ASTRA_BELOW_HEADER_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new Astra_Below_Header();
