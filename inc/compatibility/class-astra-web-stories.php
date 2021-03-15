<?php
/**
 * Web Stories Compatibility File.
 *
 * @link https://wp.stories.google/
 *
 * @package Astra
 */

use \Google\Web_Stories\Customizer;

// If plugin - '\Google\Web_Stories' not exist then return.
if ( ! class_exists( 'Google\Web_Stories\Customizer' ) ) {
	return;
}
/**
 * Astra Web_Stories Compatibility
 *
 * @since 1.0.0
 */
class Astra_Web_Stories {

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
		add_action( 'after_setup_theme', array( $this, 'web_stories_setup' ) );
		add_action( 'wp_body_open', array( $this, 'web_stories_embed' ) );
	}

	/**
	 * Add theme support for Web Stories.
	 */
	public function web_stories_setup() {
		// Enable web stories customizer support.
		add_theme_support( 'web-stories' );
	} // end function web_stories_setup

	/**
	 * Custom render function for Web Stories Embedding.
	 */
	public function web_stories_embed() {
		// Embed web stories above header with pre-configured customizer settings.
		if ( function_exists( '\Google\Web_Stories\render_theme_stories' ) ) {
			\Google\Web_Stories\render_theme_stories();

			?>
			<style type="text/css">
			.web-stories-list.web-stories-list--customizer.is-view-type-circles {
				border-bottom: 1px solid #ccc;
				padding: 15px 0;
				margin-bottom: 0;
			}
			</style>
			<?php
		}
	} // end function web_stories_embed

}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Web_Stories::get_instance();
