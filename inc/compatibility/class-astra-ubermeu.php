<?php
/**
 * Ubermenu Compatibility File.
 *
 * @link https://Ubermenu.me/
 * @since  1.1.7
 *
 * @package Astra
 */

// If plugin - 'Ubermenu' not exist then return.
if ( ! class_exists( 'UberMenu' ) ) {
	return;
}

/**
 * Astra Ubermenu Compatibility
 */
if ( ! class_exists( 'Astra_Ubermeu' ) ) :

	/**
	 * Astra Ubermenu Compatibility
	 *
	 * @since 1.0.0
	 */
	class Astra_Ubermeu {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since  1.1.7
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since  1.1.7
		 */
		public function __construct() {
			add_filter( 'astra_enable_mobile_menu_buttons', array( $this, 'disable_primary_menu_toggle' ), 30 );
		}

		/**
		 * Disable the Mobile Menu toggles from Astra if Uber Menu is used.
		 *
		 * @since  1.1.7
		 * @param  bool $status Status if the mobile menu toggels are enaled or disaled.
		 * @return bool         False If ubermenu is configured on this location. Unchanged if it is not configured.
		 */
		public function disable_primary_menu_toggle( $status ) {

			// Don't overrde anythign if ubermenu's function is not present.
			if ( ! function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) ) {
				return $status;
			}

			$ubermenu_auto_config = ubermenu_get_menu_instance_by_theme_location( 'primary' );

			// If ubermenu's auto configuration is not used here, don't override the filter.
			if ( '' === $ubermenu_auto_config || false === $ubermenu_auto_config ) {
				return $status;
			}

			return false;
		}

	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Ubermeu::get_instance();
