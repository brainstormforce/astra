<?php
/**
 * Cornerstone Compatibility File.
 *
 * @package Astra
 */

// If plugin - 'Cornerstone' not exist then return.
if ( ! class_exists( 'Cornerstone_Plugin' ) ) {
	return;
}

/**
 * Astra Cornerstone Compatibility
 */
if ( ! class_exists( 'Astra_Cornerstone' ) ) :

	/**
	 * Astra Cornerstone Compatibility
	 *
	 * @since 1.0.0
	 */
	class Astra_Cornerstone {

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
			add_filter( 'body_class', 		array( $this, 'add_body_class' ) );
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
			$assets['css']['astra-cornerstone'] = 'site-compatible/cornerstone' ;
			return $assets;
		}

		/**
		 * Astra add_body_class
		 *
		 * @param array $classes Classes for the body element.
		 *
		 * @return array
		 */
		function add_body_class( $classes ) {

			global $post;

			if ( is_singular() ) {
				if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'cs_content' ) ) {
					$classes[] = 'ast-cornerstone-compatibility';
				}
			}
			return $classes;
		}

	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Cornerstone::get_instance();
