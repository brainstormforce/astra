<?php
/**
 * Web Stories Compatibility File.
 *
 * @link https://wp.stories.google/
 *
 * @package Astra
 */

use Google\Web_Stories;

// If plugin - 'Google\Web_Stories' not exist then return.
if ( ! class_exists( 'Google\Web_Stories\Customizer' ) ) {
	return;
}

/**
 * Astra Web_Stories Compatibility
 *
 * @since 3.2.0
 */
class Astra_Web_Stories {

	/**
	 * Constructor
	 *
	 * @since 3.2.0
	 * @return void
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'web_stories_setup' ) );
		add_action( 'astra_body_top', array( $this, 'web_stories_embed' ) );
		add_filter( 'astra_dynamic_theme_css', array( $this, 'web_stories_css' ) );
	}

	/**
	 * Add theme support for Web Stories.
	 *
	 * @since 3.2.0
	 * @return void
	 */
	public function web_stories_setup() {
		add_theme_support( 'web-stories' );
	}

	/**
	 * Custom render function for Web Stories Embedding.
	 *
	 * @since 3.2.0
	 * @return void
	 */
	public function web_stories_embed() {
		if ( ! function_exists( 'Google\Web_Stories\render_theme_stories' ) ) {
			return;
		}

		// Embed web stories above header with pre-configured customizer settings.
		Web_Stories\render_theme_stories();
	}

	/**
	 * Add dynamic CSS for the webstories.
	 *
	 * @since 3.2.0
	 *
	 * @param  string $dynamic_css          Astra Dynamic CSS.
	 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
	 *
	 * @return String Generated dynamic CSS for Heading Colors.
	 */
	public function web_stories_css( $dynamic_css, $dynamic_css_filtered = '' ) {
		$options = get_option( Web_Stories\Customizer::STORY_OPTION );

		// bail if web stories are not enabled on the frontend.
		if ( empty( $options['show_stories'] ) || true !== $options['show_stories'] ) {
			return $dynamic_css;
		}

		$stories_css_array = array(
			'.web-stories-list.web-stories-list--customizer.is-view-type-circles' => array(
				'border-bottom' => '1px solid #ccc',
				'padding'       => '15px 0',
				'margin-bottom' => '0',
			),
		);

		$dynamic_css .= astra_parse_css( $stories_css_array );

		return $dynamic_css;
	}

}

new Astra_Web_Stories();
