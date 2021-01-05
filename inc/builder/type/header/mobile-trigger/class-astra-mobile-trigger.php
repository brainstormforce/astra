<?php
/**
 * Mobile Trigger.
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

define( 'ASTRA_MOBILE_TRIGGER_DIR', ASTRA_THEME_DIR . 'inc/builder/type/header/mobile-trigger' );
define( 'ASTRA_MOBILE_TRIGGER_URI', ASTRA_THEME_URI . 'inc/builder/type/header/mobile-trigger' );

/**
 * Mobile Trigger Initial Setup
 *
 * @since 3.0.0
 */
class Astra_Mobile_Trigger {

	/**
	 * Constructor function that initializes required actions and hooks.
	 */
	public function __construct() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once ASTRA_MOBILE_TRIGGER_DIR . '/class-astra-mobile-trigger-loader.php';

		// Include front end files.
		if ( ! is_admin() ) {
			require_once ASTRA_MOBILE_TRIGGER_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new Astra_Mobile_Trigger();
