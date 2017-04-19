<?php
/**
 * Theme Hook Alliance hook stub list.
 *
 * @see  https://github.com/zamoose/themehookalliance
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.0.0
 */

/**
 * Themes and Plugins can check for ast_hooks using current_theme_supports( 'ast_hooks', $hook )
 * to determine whether a theme declares itself to support this specific hook type.
 *
 * Example:
 * <code>
 * 		// Declare support for all hook types
 * 		add_theme_support( 'ast_hooks', array( 'all' ) );
 *
 * 		// Declare support for certain hook types only
 * 		add_theme_support( 'ast_hooks', array( 'header', 'content', 'footer' ) );
 * </code>
 */
add_theme_support( 'ast_hooks', array(

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
) );

/**
 * Determines, whether the specific hook type is actually supported.
 *
 * Plugin developers should always check for the support of a <strong>specific</strong>
 * hook type before hooking a callback function to a hook of this type.
 *
 * Example:
 * <code>
 * 		if ( current_theme_supports( 'ast_hooks', 'header' ) )
 * 	  		add_action( 'ast_head_top', 'prefix_header_top' );
 * </code>
 *
 * @param bool  $bool true.
 * @param array $args The hook type being checked.
 * @param array $registered All registered hook types.
 *
 * @return bool
 */
function ast_current_theme_supports( $bool, $args, $registered ) {
	return in_array( $args[0], $registered[0] ) || in_array( 'all', $registered[0] );
}
add_filter( 'current_theme_supports-ast_hooks', 'ast_current_theme_supports', 10, 3 );

/**
 * HTML <html> hook
 * Special case, useful for <DOCTYPE>, etc.
 * $ast_supports[] = 'html;
 */
function ast_html_before() {
	do_action( 'ast_html_before' );
}
/**
 * HTML <body> hooks
 * $ast_supports[] = 'body';
 */
function ast_body_top() {
	do_action( 'ast_body_top' );
}

/**
 * Body Bottom
 */
function ast_body_bottom() {
	do_action( 'ast_body_bottom' );
}

/**
 * HTML <head> hooks
 *
 * $ast_supports[] = 'head';
 */
function ast_head_top() {
	do_action( 'ast_head_top' );
}

/**
 * Head Bottom
 */
function ast_head_bottom() {
	do_action( 'ast_head_bottom' );
}

/**
 * Semantic <header> hooks
 *
 * $ast_supports[] = 'header';
 */
function ast_header_before() {
	do_action( 'ast_header_before' );
}

/**
 * Site Header
 */
function ast_header() {
	do_action( 'ast_header' );
}

/**
 * Masthead Top
 */
function ast_masthead_top() {
	do_action( 'ast_masthead_top' );
}

/**
 * Masthead
 */
function ast_masthead() {
	do_action( 'ast_masthead' );
}

/**
 * Masthead Bottom
 */
function ast_masthead_bottom() {
	do_action( 'ast_masthead_bottom' );
}

/**
 * Header After
 */
function ast_header_after() {
	do_action( 'ast_header_after' );
}

/**
 * Main Header bar top
 */
function ast_main_header_bar_top() {
	do_action( 'ast_main_header_bar_top' );
}

/**
 * Main Header bar bottom
 */
function ast_main_header_bar_bottom() {
	do_action( 'ast_main_header_bar_bottom' );
}

/**
 * Main Header Content
 */
function ast_masthead_content() {
	do_action( 'ast_masthead_content' );
}
/**
 * Main toggle button before
 */
function ast_masthead_toggle_buttons_before() {
	do_action( 'ast_masthead_toggle_buttons_before' );
}

/**
 * Main toggle buttons
 */
function ast_masthead_toggle_buttons() {
	do_action( 'ast_masthead_toggle_buttons' );
}

/**
 * Main toggle button after
 */
function ast_masthead_toggle_buttons_after() {
	do_action( 'ast_masthead_toggle_buttons_after' );
}

/**
 * Semantic <content> hooks
 *
 * $ast_supports[] = 'content';
 */
function ast_content_before() {
	do_action( 'ast_content_before' );
}

/**
 * Content after
 */
function ast_content_after() {
	do_action( 'ast_content_after' );
}

/**
 * Content top
 */
function ast_content_top() {
	do_action( 'ast_content_top' );
}

/**
 * Content bottom
 */
function ast_content_bottom() {
	do_action( 'ast_content_bottom' );
}

/**
 * Content while before
 */
function ast_content_while_before() {
	do_action( 'ast_content_while_before' );
}

/**
 * Content while after
 */
function ast_content_while_after() {
	do_action( 'ast_content_while_after' );
}

/**
 * Semantic <entry> hooks
 *
 * $ast_supports[] = 'entry';
 */
function ast_entry_before() {
	do_action( 'ast_entry_before' );
}

/**
 * Entry after
 */
function ast_entry_after() {
	do_action( 'ast_entry_after' );
}

/**
 * Entry content before
 */
function ast_entry_content_before() {
	do_action( 'ast_entry_content_before' );
}

/**
 * Entry content after
 */
function ast_entry_content_after() {
	do_action( 'ast_entry_content_after' );
}

/**
 * Entry Top
 */
function ast_entry_top() {
	do_action( 'ast_entry_top' );
}

/**
 * Entry bottom
 */
function ast_entry_bottom() {
	do_action( 'ast_entry_bottom' );
}

/**
 * Single Post Header Before
 */
function ast_single_header_before() {
	do_action( 'ast_single_header_before' );
}

/**
 * Single Post Header After
 */
function ast_single_header_after() {
	do_action( 'ast_single_header_after' );
}

/**
 * Single Post Header Top
 */
function ast_single_header_top() {
	do_action( 'ast_single_header_top' );
}

/**
 * Single Post Header Bottom
 */
function ast_single_header_bottom() {
	do_action( 'ast_single_header_bottom' );
}

/**
 * Comments block hooks
 *
 * $ast_supports[] = 'comments';
 */
function ast_comments_before() {
	do_action( 'ast_comments_before' );
}

/**
 * Comments after.
 */
function ast_comments_after() {
	do_action( 'ast_comments_after' );
}

/**
 * Semantic <sidebar> hooks
 *
 * $ast_supports[] = 'sidebar';
 */
function ast_sidebars_before() {
	do_action( 'ast_sidebars_before' );
}

/**
 * Sidebars after
 */
function ast_sidebars_after() {
	do_action( 'ast_sidebars_after' );
}

/**
 * Semantic <footer> hooks
 *
 * $ast_supports[] = 'footer';
 */
function ast_footer() {
	do_action( 'ast_footer' );
}

/**
 * Footer before
 */
function ast_footer_before() {
	do_action( 'ast_footer_before' );
}

/**
 * Footer after
 */
function ast_footer_after() {
	do_action( 'ast_footer_after' );
}

/**
 * Footer top
 */
function ast_footer_content_top() {
	do_action( 'ast_footer_content_top' );
}

/**
 * Footer
 */
function ast_footer_content() {
	do_action( 'ast_footer_content' );
}

/**
 * Footer bottom
 */
function ast_footer_content_bottom() {
	do_action( 'ast_footer_content_bottom' );
}

/**
 * Archive header
 */
function ast_archive_header() {
	do_action( 'ast_archive_header' );
}

/**
 * Pagination
 */
function ast_pagination() {
	do_action( 'ast_pagination' );
}

/**
 * Entry content single
 */
function ast_entry_content_single() {
	do_action( 'ast_entry_content_single' );
}

/**
 * 404
 */
function ast_entry_content_404_page() {
	do_action( 'ast_entry_content_404_page' );
}

/**
 * Entry content blog
 */
function ast_entry_content_blog() {
	do_action( 'ast_entry_content_blog' );
}

/**
 * Blog featured post section
 */
function ast_blog_post_featured_format() {
	do_action( 'ast_blog_post_featured_format' );
}
