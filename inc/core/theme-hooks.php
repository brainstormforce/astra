<?php
/**
 * Theme Hook Alliance hook stub list.
 *
 * @see  https://github.com/zamoose/themehookalliance
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Themes and Plugins can check for astra_hooks using current_theme_supports( 'astra_hooks', $hook )
 * to determine whether a theme declares itself to support this specific hook type.
 *
 * Example:
 * <code>
 *      // Declare support for all hook types
 *      add_theme_support( 'astra_hooks', array( 'all' ) );
 *
 *      // Declare support for certain hook types only
 *      add_theme_support( 'astra_hooks', array( 'header', 'content', 'footer' ) );
 * </code>
 */
add_theme_support(
	'astra_hooks',
	array(

		/**
		 * As a Theme developer, use the 'all' parameter, to declare support for all
		 * hook types.
		 * Please make sure you then actually reference all the hooks in this file,
		 * Plugin developers depend on it!
		 */
		'all',

		/**
		 * Themes can also choose to only support certain hook types.
		 * Please make sure you then actually reference all the hooks in this type
		 * family.
		 *
		 * When the 'all' parameter was set, specific hook types do not need to be
		 * added explicitly.
		 */
		'html',
		'body',
		'head',
		'header',
		'content',
		'entry',
		'comments',
		'sidebars',
		'sidebar',
		'footer',

	/**
	 * If/when WordPress Core implements similar methodology, Themes and Plugins
	 * will be able to check whether the version of THA supplied by the theme
	 * supports Core hooks.
	 */
	)
);

/**
 * Determines, whether the specific hook type is actually supported.
 *
 * Plugin developers should always check for the support of a <strong>specific</strong>
 * hook type before hooking a callback function to a hook of this type.
 *
 * Example:
 * <code>
 *      if ( current_theme_supports( 'astra_hooks', 'header' ) )
 *          add_action( 'astra_head_top', 'prefix_header_top' );
 * </code>
 *
 * @param bool  $bool true.
 * @param array $args The hook type being checked.
 * @param array $registered All registered hook types.
 *
 * @return bool
 */
function astra_current_theme_supports( $bool, $args, $registered ) {
	return in_array( $args[0], $registered[0] ) || in_array( 'all', $registered[0] );
}
add_filter( 'current_theme_supports-astra_hooks', 'astra_current_theme_supports', 10, 3 );

/**
 * HTML <html> hook
 * Special case, useful for <DOCTYPE>, etc.
 * $astra_supports[] = 'html;
 */
function astra_html_before() {
	do_action( 'astra_html_before' );
}
/**
 * HTML <body> hooks
 * $astra_supports[] = 'body';
 */
function astra_body_top() {
	do_action( 'astra_body_top' );
}

/**
 * Body Bottom
 */
function astra_body_bottom() {
	do_action( 'astra_body_bottom' );
}

/**
 * HTML <head> hooks
 *
 * $astra_supports[] = 'head';
 */
function astra_head_top() {
	do_action( 'astra_head_top' );
}

/**
 * Head Bottom
 */
function astra_head_bottom() {
	do_action( 'astra_head_bottom' );
}

/**
 * Semantic <header> hooks
 *
 * $astra_supports[] = 'header';
 */
function astra_header_before() {
	do_action( 'astra_header_before' );
}

/**
 * Site Header
 */
function astra_header() {
	do_action( 'astra_header' );
}

/**
 * Masthead Top
 */
function astra_masthead_top() {
	do_action( 'astra_masthead_top' );
}

/**
 * Masthead
 */
function astra_masthead() {
	do_action( 'astra_masthead' );
}

/**
 * Masthead Bottom
 */
function astra_masthead_bottom() {
	do_action( 'astra_masthead_bottom' );
}

/**
 * Header After
 */
function astra_header_after() {
	do_action( 'astra_header_after' );
}

/**
 * Main Header bar top
 */
function astra_main_header_bar_top() {
	do_action( 'astra_main_header_bar_top' );
}

/**
 * Main Header bar bottom
 */
function astra_main_header_bar_bottom() {
	do_action( 'astra_main_header_bar_bottom' );
}

/**
 * Main Header Content
 */
function astra_masthead_content() {
	do_action( 'astra_masthead_content' );
}
/**
 * Main toggle button before
 */
function astra_masthead_toggle_buttons_before() {
	do_action( 'astra_masthead_toggle_buttons_before' );
}

/**
 * Main toggle buttons
 */
function astra_masthead_toggle_buttons() {
	do_action( 'astra_masthead_toggle_buttons' );
}

/**
 * Main toggle button after
 */
function astra_masthead_toggle_buttons_after() {
	do_action( 'astra_masthead_toggle_buttons_after' );
}

/**
 * Semantic <content> hooks
 *
 * $astra_supports[] = 'content';
 */
function astra_content_before() {
	do_action( 'astra_content_before' );
}

/**
 * Content after
 */
function astra_content_after() {
	do_action( 'astra_content_after' );
}

/**
 * Content top
 */
function astra_content_top() {
	do_action( 'astra_content_top' );
}

/**
 * Content bottom
 */
function astra_content_bottom() {
	do_action( 'astra_content_bottom' );
}

/**
 * Content while before
 */
function astra_content_while_before() {
	do_action( 'astra_content_while_before' );
}

/**
 * Content loop
 */
function astra_content_loop() {
	do_action( 'astra_content_loop' );
}

/**
 * Conten Page Loop.
 *
 * Called from page.php
 */
function astra_content_page_loop() {
	do_action( 'astra_content_page_loop' );
}

/**
 * Content while after
 */
function astra_content_while_after() {
	do_action( 'astra_content_while_after' );
}

/**
 * Semantic <entry> hooks
 *
 * $astra_supports[] = 'entry';
 */
function astra_entry_before() {
	do_action( 'astra_entry_before' );
}

/**
 * Entry after
 */
function astra_entry_after() {
	do_action( 'astra_entry_after' );
}

/**
 * Entry content before
 */
function astra_entry_content_before() {
	do_action( 'astra_entry_content_before' );
}

/**
 * Entry content after
 */
function astra_entry_content_after() {
	do_action( 'astra_entry_content_after' );
}

/**
 * Entry Top
 */
function astra_entry_top() {
	do_action( 'astra_entry_top' );
}

/**
 * Entry bottom
 */
function astra_entry_bottom() {
	do_action( 'astra_entry_bottom' );
}

/**
 * Single Post Header Before
 */
function astra_single_header_before() {
	do_action( 'astra_single_header_before' );
}

/**
 * Single Post Header After
 */
function astra_single_header_after() {
	do_action( 'astra_single_header_after' );
}

/**
 * Single Post Header Top
 */
function astra_single_header_top() {
	do_action( 'astra_single_header_top' );
}

/**
 * Single Post Header Bottom
 */
function astra_single_header_bottom() {
	do_action( 'astra_single_header_bottom' );
}

/**
 * Comments block hooks
 *
 * $astra_supports[] = 'comments';
 */
function astra_comments_before() {
	do_action( 'astra_comments_before' );
}

/**
 * Comments after.
 */
function astra_comments_after() {
	do_action( 'astra_comments_after' );
}

/**
 * Semantic <sidebar> hooks
 *
 * $astra_supports[] = 'sidebar';
 */
function astra_sidebars_before() {
	do_action( 'astra_sidebars_before' );
}

/**
 * Sidebars after
 */
function astra_sidebars_after() {
	do_action( 'astra_sidebars_after' );
}

/**
 * Semantic <footer> hooks
 *
 * $astra_supports[] = 'footer';
 */
function astra_footer() {
	do_action( 'astra_footer' );
}

/**
 * Footer before
 */
function astra_footer_before() {
	do_action( 'astra_footer_before' );
}

/**
 * Footer after
 */
function astra_footer_after() {
	do_action( 'astra_footer_after' );
}

/**
 * Footer top
 */
function astra_footer_content_top() {
	do_action( 'astra_footer_content_top' );
}

/**
 * Footer
 */
function astra_footer_content() {
	do_action( 'astra_footer_content' );
}

/**
 * Footer bottom
 */
function astra_footer_content_bottom() {
	do_action( 'astra_footer_content_bottom' );
}

/**
 * Archive header
 */
function astra_archive_header() {
	do_action( 'astra_archive_header' );
}

/**
 * Pagination
 */
function astra_pagination() {
	do_action( 'astra_pagination' );
}

/**
 * Entry content single
 */
function astra_entry_content_single() {
	do_action( 'astra_entry_content_single' );
}

/**
 * 404
 */
function astra_entry_content_404_page() {
	do_action( 'astra_entry_content_404_page' );
}

/**
 * Entry content blog
 */
function astra_entry_content_blog() {
	do_action( 'astra_entry_content_blog' );
}

/**
 * Blog featured post section
 */
function astra_blog_post_featured_format() {
	do_action( 'astra_blog_post_featured_format' );
}

/**
 * Primary Content Top
 */
function astra_primary_content_top() {
	do_action( 'astra_primary_content_top' );
}

/**
 * Primary Content Bottom
 */
function astra_primary_content_bottom() {
	do_action( 'astra_primary_content_bottom' );
}

/**
 * 404 Page content template action.
 */
function astra_404_content_template() {
	do_action( 'astra_404_content_template' );
}

if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * Fire the wp_body_open action.
	 * Adds backward compatibility for WordPress versions < 5.2
	 *
	 * @since 1.8.7
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}
