<?php
/**
 * Single Actions.
 *
 * @package Astra
 * @since 1.0.0
 */

/**
 * Single Content Main Template
 *
 * => Used in files:
 *
 * /single.php
 *
 * @since 1.0.0
 */
function astra_single_content_template() {
	get_template_part( 'template-parts/content', 'single' );
}

/**
 * Single Post Comments
 *
 * => Used in files:
 *
 * /template-parts/content-single.php
 *
 * @since 1.0.0
 */
function astra_single_comments() {

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
}


/**
 * Single post markup ( template part )
 *
 * => Used in files:
 *
 * /template-parts/content-single.php
 *
 * @since 1.0.0
 */
function astra_entry_content_single_template() {
	get_template_part( 'template-parts/single/single-layout' );
}

/**
 * Single Post Content
 *
 * => Used in files:
 *
 * /template-parts/single/single-layout.php
 *
 * @since 1.0.0
 */
function astra_single_content() {
	the_content();
}

/**
 * Single Edit Post Link
 *
 * => Used in files:
 *
 * /template-parts/single/single-layout.php
 *
 * @since 1.0.0
 */
function astra_entry_single_the_edit_post_link() {
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

/**
 * Single Post Content Pagination
 *
 * => Used in files:
 *
 * /template-parts/single/single-layout.php
 *
 * @since 1.0.0
 */
function astra_entry_single_content_the_link_pages() {
	wp_link_pages(
		array(
			'before'      => '<div class="page-links">' . esc_html( astra_default_strings( 'string-single-page-links-before', false ) ),
			'after'       => '</div>',
			'link_before' => '<span class="page-link">',
			'link_after'  => '</span>',
		)
	);
}
