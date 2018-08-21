<?php
/**
 * Astra Theme Strings
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

/**
 * Default Strings
 */
if ( ! function_exists( 'astra_default_strings' ) ) {

	/**
	 * Default Strings
	 *
	 * @since 1.0.0
	 * @param  string  $key  String key.
	 * @param  boolean $echo Print string.
	 * @return mixed        Return string or nothing.
	 */
	function astra_default_strings( $key, $echo = true ) {

		$defaults = apply_filters(
			'astra_default_strings', array(

				// Header.
				'string-header-skip-link'                => __( 'Skip to content', 'astra' ),

				// 404 Page Strings.
				'string-404-sub-title'                   => __( 'It looks like the link pointing here was faulty. Maybe try searching?', 'astra' ),

				// Search Page Strings.
				'string-search-nothing-found'            => __( 'Nothing Found', 'astra' ),
				'string-search-nothing-found-message'    => __( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'astra' ),
				'string-full-width-search-message'       => __( 'Start typing and press enter to search', 'astra' ),
				'string-full-width-search-placeholder'   => __( 'Start Typing&hellip;', 'astra' ),
				'string-header-cover-search-placeholder' => __( 'Start Typing&hellip;', 'astra' ),
				'string-search-input-placeholder'        => __( 'Search &hellip;', 'astra' ),

				// Comment Template Strings.
				'string-comment-reply-link'              => __( 'Reply', 'astra' ),
				'string-comment-edit-link'               => __( 'Edit', 'astra' ),
				'string-comment-awaiting-moderation'     => __( 'Your comment is awaiting moderation.', 'astra' ),
				'string-comment-title-reply'             => __( 'Leave a Comment', 'astra' ),
				'string-comment-cancel-reply-link'       => __( 'Cancel Reply', 'astra' ),
				'string-comment-label-submit'            => __( 'Post Comment &raquo;', 'astra' ),
				'string-comment-label-message'           => __( 'Type here..', 'astra' ),
				'string-comment-label-name'              => __( 'Name*', 'astra' ),
				'string-comment-label-email'             => __( 'Email*', 'astra' ),
				'string-comment-label-website'           => __( 'Website', 'astra' ),
				'string-comment-closed'                  => __( 'Comments are closed.', 'astra' ),
				'string-comment-navigation-title'        => __( 'Comment navigation', 'astra' ),
				'string-comment-navigation-next'         => __( 'Newer Comments', 'astra' ),
				'string-comment-navigation-previous'     => __( 'Older Comments', 'astra' ),

				// Blog Default Strings.
				'string-blog-page-links-before'          => __( 'Pages:', 'astra' ),
				'string-blog-meta-author-by'             => __( 'By ', 'astra' ),
				'string-blog-meta-leave-a-comment'       => __( 'Leave a Comment', 'astra' ),
				'string-blog-meta-one-comment'           => __( '1 Comment', 'astra' ),
				'string-blog-meta-multiple-comment'      => __( '% Comments', 'astra' ),
				'string-blog-navigation-next'            => __( 'Next Page', 'astra' ) . ' <span class="ast-right-arrow">&rarr;</span>',
				'string-blog-navigation-previous'        => '<span class="ast-left-arrow">&larr;</span> ' . __( 'Previous Page', 'astra' ),

				// Single Post Default Strings.
				'string-single-page-links-before'        => __( 'Pages:', 'astra' ),
				/* translators: 1: Post type label */
				'string-single-navigation-next'          => __( 'Next %s', 'astra' ) . ' <span class="ast-right-arrow">&rarr;</span>',
				/* translators: 1: Post type label */
				'string-single-navigation-previous'      => '<span class="ast-left-arrow">&larr;</span> ' . __( 'Previous %s', 'astra' ),

				// Content None.
				'string-content-nothing-found-message'   => __( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'astra' ),

			)
		);

		if ( is_rtl() ) {
			$defaults['string-blog-navigation-next']     = __( 'Next Page', 'astra' ) . ' <span class="ast-left-arrow">&larr;</span>';
			$defaults['string-blog-navigation-previous'] = '<span class="ast-right-arrow">&rarr;</span> ' . __( 'Previous Page', 'astra' );

			/* translators: 1: Post type label */
			$defaults['string-single-navigation-next'] = __( 'Next %s', 'astra' ) . ' <span class="ast-left-arrow">&larr;</span>';
			/* translators: 1: Post type label */
			$defaults['string-single-navigation-previous'] = '<span class="ast-right-arrow">&rarr;</span> ' . __( 'Previous %s', 'astra' );
		}

		$output = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

		/**
		 * Print or return
		 */
		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
}
