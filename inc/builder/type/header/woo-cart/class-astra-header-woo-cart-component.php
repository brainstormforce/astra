<?php
/**
 * WooCommerce Cart for Astra theme.
 *
 * @package     astra-builder
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ASTRA_HEADER_WOO_CART_DIR', ASTRA_THEME_DIR . 'inc/builder/type/header/woo-cart' );
define( 'ASTRA_HEADER_WOO_CART_URI', ASTRA_THEME_URI . 'inc/builder/type/header/woo-cart' );

if ( ! class_exists( 'Astra_Header_Woo_Cart_Component' ) ) {

	/**
	 * Heading Initial Setup
	 *
	 * @since 3.0.0
	 */
	class Astra_Header_Woo_Cart_Component {

		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once ASTRA_HEADER_WOO_CART_DIR . '/class-astra-header-woo-cart-loader.php';

			// Include front end files.
			if ( ! is_admin() ) {
				require_once ASTRA_HEADER_WOO_CART_DIR . '/dynamic-css/dynamic.css.php';
			}
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}

	/**
	 *  Kicking this off by creating an object.
	 */
	new Astra_Header_Woo_Cart_Component();

}
