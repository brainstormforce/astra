<?php
/**
 * Gravity Forms File.
 *
 * @package Astra
 */

// If plugin - 'Gravity Forms' not exist then return.
if ( ! class_exists( 'GFForms' ) ) {
	return;
}

/**
 * Astra Gravity Forms
 */
if ( ! class_exists( 'Astra_Gravity_Forms' ) ) :

	/**
	 * Astra Gravity Forms
	 *
	 * @since 1.0.0
	 */
	class Astra_Gravity_Forms {

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
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'gform_enqueue_scripts', array( $this, 'add_styles' ) );
		}

		/**
		 * Add assets in theme
		 *
		 * @since 1.0.0
		 */
		function add_styles() {
			$file_prefix = ( SCRIPT_DEBUG ) ? '' : '.min';
			$dir_name    = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';

			if ( is_rtl() ) {
				$file_prefix .= '-rtl';
			}

			$css_file = ASTRA_THEME_URI . 'assets/css/' . $dir_name . '/compatibility/gravity-forms' . $file_prefix . '.css';

			wp_enqueue_style( 'astra-gravity-forms', $css_file, array(), ASTRA_THEME_VERSION, 'all' );
		}

	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Gravity_Forms::get_instance();
