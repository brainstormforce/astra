<?php
/**
 * Page Actions.
 *
 * @package Astra
 * @since 1.0.0
 */

/**
 * Page Content Main Template
 *
 * => Used in files:
 *
 * /page.php
 *
 * @since 1.0.0
 */
function astra_page_content_template() {
	get_template_part( 'template-parts/content', 'page' );
}

/**
 * Page Comments
 *
 * => Used in files:
 *
 * /template-parts/content-page.php
 *
 * @since 1.0.0
 */
function astra_page_comments() {

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
}


/**
 * Page markup ( template part )
 *
 * => Used in files:
 *
 * /template-parts/content-page.php
 *
 * @since 1.0.0
 */
function astra_entry_content_page_template() {
	get_template_part( 'template-parts/page/page-layout' );
}

/**
 * Page Content
 *
 * => Used in files:
 *
 * /template-parts/single/page-layout.php
 *
 * @since 1.0.0
 */
function astra_page_content() {
	the_content();
}

/**
 * Page Featured Image.
 *
 * => Used in files:
 *
 * /template-parts/single/page-layout.php
 *
 * @since 1.0.0
 */
function astra_entry_page_header_the_image() {
	
	astra_get_post_thumbnail();
}

/**
 * Page Title.
 *
 * => Used in files:
 *
 * /template-parts/single/page-layout.php
 *
 * @since 1.0.0
 */
function astra_entry_page_header_the_title() {
	
	astra_the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' );
}

/**
 * Page Content Pagination
 *
 * => Used in files:
 *
 * /template-parts/page/page-layout.php
 *
 * @since 1.0.0
 */
function astra_entry_page_content_the_link_pages() {
	
	wp_link_pages(
		array(
			'before'      => '<div class="page-links">' . esc_html( astra_default_strings( 'string-single-page-links-before', false ) ),
			'after'       => '</div>',
			'link_before' => '<span class="page-link">',
			'link_after'  => '</span>',
		)
	);
}

/**
 * Page Edit Link
 *
 * => Used in files:
 *
 * /template-parts/page/page-layout.php
 *
 * @since 1.0.0
 */
function astra_entry_page_the_edit_post_link() {
	astra_edit_post_link(

		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'astra' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<footer class="entry-footer"><span class="edit-link">',
		'</span></footer><!-- .entry-footer -->'
	);
}
