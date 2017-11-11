<?php
/**
 * BNE Flyout Compatibility File.
 *
 * @package Astra
 */

// If plugin - 'BNE Flyout' not exist then return.
if ( ! defined( 'BNE_FLYOUT_VERSION' ) ) {
	return;
}

/**
 * Astra BNE Flyout Compatibility
 */
if ( ! class_exists( 'Astra_BNE_Flyout' ) ) :

	/**
	 * Astra BNE Flyout Compatibility
	 *
	 * @since 1.0.0
	 */
	class Astra_BNE_Flyout {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_filter( 'astra_theme_assets', array( $this, 'add_styles' ) );
		}

		/**
		 * Add assets in theme
		 *
		 * @param array $assets list of theme assets (JS & CSS).
		 * @return array List of updated assets.
		 * @since 1.0.0
		 */
		function add_styles( $assets ) {
			$assets['css']['astra-bne-flyout'] = 'site-compatible/bne-flyout';
			return $assets;
		}

	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_BNE_Flyout::get_instance();
