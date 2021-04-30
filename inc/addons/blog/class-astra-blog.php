<?php
/**
 * Blog Extension
 *
 * @package Astra
 */

define( 'ASTRA_THEME_BLOG_DIR', ASTRA_THEME_DIR . 'inc/addons/blog/' );
define( 'ASTRA_THEME_BLOG_URI', ASTRA_THEME_URI . 'inc/addons/blog/' );

if ( ! class_exists( 'Astra_Blog' ) ) {

	/**
	 * Blog Initial Setup
	 *
	 * @since x.x.x
	 */
	class Astra_Blog {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance() { 
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {
			require_once ASTRA_THEME_BLOG_DIR . 'classes/class-astra-blog-loader.php'; // phpcs:ignore  WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once ASTRA_THEME_BLOG_DIR . 'classes/class-astra-blog-markup.php'; // phpcs:ignore  WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			// Include front end files.
			if ( ! is_admin() ) {
				require_once ASTRA_THEME_BLOG_DIR . 'classes/dynamic.php'; // phpcs:ignore  WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}

		}
	}

	/**
	 *  Kicking this off by calling 'get_instance()' method
	 */
	Astra_Blog::get_instance();

}
