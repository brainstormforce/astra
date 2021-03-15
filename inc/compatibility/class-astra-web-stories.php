<?php
/**
 * Web Stories Compatibility File.
 *
 * @link https://wp.stories.google/
 *
 * @package Astra
 */
use Google\Web_Stories;

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
	 * Constructor
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'web_stories_setup' ) );
		add_action( 'astra_body_top', array( $this, 'web_stories_embed' ) );
	}

	/**
	 * Add theme support for Web Stories.
	 */
	public function web_stories_setup() {
		add_theme_support( 'web-stories' );
	}

	/**
	 * Custom render function for Web Stories Embedding.
	 */
	public function web_stories_embed() {
		// Embed web stories above header with pre-configured customizer settings.
		if ( function_exists( '\Google\Web_Stories\render_theme_stories' ) ) {
			Web_Stories\render_theme_stories();

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
	}

}

new Astra_Web_Stories();
