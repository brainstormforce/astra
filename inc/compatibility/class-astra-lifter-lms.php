<?php
/**
 * Lifter LMS Compatibility File.
 *
 * @package Astra
 */

// If plugin - 'Lifter LMS' not exist then return.
if ( ! class_exists( 'LifterLMS' ) ) {
	return;
}

/**
 * Astra Lifter LMS Compatibility
 */
if ( ! class_exists( 'Astra_Lifter_LMS' ) ) :

	/**
	 * Astra Lifter LMS Compatibility
	 *
	 * @since 1.0.0
	 */
	class Astra_Lifter_LMS {

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

			add_filter( 'llms_get_theme_default_sidebar', array( $this, 'add_sidebar' ) );
			add_action( 'after_setup_theme', array( $this, 'add_theme_support' ) );
			add_filter( 'astra_theme_assets', array( $this, 'add_styles' ) );
		}

		/**
		 * Display LifterLMS Course and Lesson sidebars
		 * on courses and lessons in place of the sidebar returned by
		 * this function
		 *
		 * @param    string $id    default sidebar id (an empty string).
		 * @return   string
		 */
		function add_sidebar( $id ) {
			$sidebar_id = 'sidebar-1'; // replace this with your theme's sidebar ID.
			return $sidebar_id;
		}

		/**
		 * Declare explicit theme support for LifterLMS course and lesson sidebars
		 *
		 * @return   void
		 */
		function add_theme_support() {
			add_theme_support( 'lifterlms-sidebars' );
		}

		/**
		 * Add assets in theme
		 *
		 * @param array $assets list of theme assets (JS & CSS).
		 * @return array List of updated assets.
		 * @since 1.0.0
		 */
		function add_styles( $assets ) {
			$assets['css']['astra-lifter-lms'] = 'site-compatible/lifter-lms';
			return $assets;
		}

	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Lifter_LMS::get_instance();
