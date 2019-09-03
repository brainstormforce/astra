<?php
/**
 * Sticky Header Extension
 *
 * @package Astra Addon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ASTRA_THEME_TRANSPARENT_HEADER_DIR', ASTRA_THEME_DIR . 'inc/addons/transparent-header/' );
define( 'ASTRA_THEME_TRANSPARENT_HEADER_URI', ASTRA_THEME_URI . 'inc/addons/transparent-header/' );

if ( ! class_exists( 'Astra_Ext_Transparent_Header' ) ) {

	/**
	 * Sticky Header Initial Setup
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Transparent_Header {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			require_once ASTRA_THEME_TRANSPARENT_HEADER_DIR . 'classes/class-astra-ext-transparent-header-loader.php';
			require_once ASTRA_THEME_TRANSPARENT_HEADER_DIR . 'classes/class-astra-ext-transparent-header-markup.php';

			// Include front end files.
			if ( ! is_admin() ) {
				require_once ASTRA_THEME_TRANSPARENT_HEADER_DIR . 'classes/dynamic-css/dynamic.css.php';
				require_once ASTRA_THEME_TRANSPARENT_HEADER_DIR . 'classes/dynamic-css/header-sections-dynamic.css.php';
			}
		}
	}

	/**
	 *  Kicking this off by calling 'get_instance()' method
	 */
	Astra_Ext_Transparent_Header::get_instance();

}
