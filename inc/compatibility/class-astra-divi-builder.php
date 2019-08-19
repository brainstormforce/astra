<?php
/**
 * Divi Builder File.
 *
 * @package Astra
 */

// If plugin - 'Divi Builder' not exist then return.
if ( ! class_exists( 'ET_Builder_Plugin' ) ) {
	return;
}

/**
 * Astra Divi Builder
 */
if ( ! class_exists( 'Astra_Divi_Builder' ) ) :

	/**
	 * Astra Divi Builder
	 *
	 * @since 1.4.0
	 */
	class Astra_Divi_Builder {

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
		 * @since 1.4.0
		 */
		function add_styles( $assets ) {
			$assets['css']['astra-divi-builder'] = 'compatibility/divi-builder';
			return $assets;
		}

	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Divi_Builder::get_instance();
