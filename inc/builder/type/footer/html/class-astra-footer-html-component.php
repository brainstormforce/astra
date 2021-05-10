<?php
/**
 * HTML component.
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

define( 'ASTRA_BUILDER_FOOTER_HTML_DIR', ASTRA_THEME_DIR . 'inc/builder/type/footer/html' );
define( 'ASTRA_BUILDER_FOOTER_HTML_URI', ASTRA_THEME_URI . 'inc/builder/type/footer/html' );

/**
 * Heading Initial Setup
 *
 * @since 3.0.0
 */
class Astra_Footer_Html_Component {

	/**
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once ASTRA_BUILDER_FOOTER_HTML_DIR . '/class-astra-footer-html-component-loader.php';

		// Include front end files.
		if ( ! is_admin() ) {
			require_once ASTRA_BUILDER_FOOTER_HTML_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new Astra_Footer_Html_Component();
