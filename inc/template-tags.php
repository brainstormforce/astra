<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Astra
 */

if ( ! function_exists( 'astra_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function astra_entry_footer() {

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';

			/**
			 * Get default strings.
			 *
			 * @see astra_default_strings
			 */
			comments_popup_link( astra_default_strings( 'string-blog-meta-leave-a-comment', false ), astra_default_strings( 'string-blog-meta-one-comment', false ), astra_default_strings( 'string-blog-meta-multiple-comment', false ) );
			echo '</span>';
		}

		astra_edit_post_link(

			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'astra' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function astra_categorized_blog() {
	$all_the_cool_cats = get_transient( 'astra_categories' );
	if ( false === $all_the_cool_cats ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories(
			array(
				'fields'     => 'ids',
				'hide_empty' => 1,

				// We only need to know if there is more than one category.
				'number'     => 2,
			)
		);

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'astra_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so astra_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so astra_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in astra_categorized_blog.
 */
function astra_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'astra_categories' );
}
add_action( 'edit_category', 'astra_category_transient_flusher' );
add_action( 'save_post',     'astra_category_transient_flusher' );
