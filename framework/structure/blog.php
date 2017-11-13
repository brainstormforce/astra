<?php
/**
 * Blog Actions.
 *
 * @package Astra
 * @since 1.0.0
 */

/**
 * Content Blog Templates.
 *
 * => Action hooked 'astra_entry_content_blog'
 * 
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_content_blog', 'astra_entry_content_blog_template' );

/**
 * Content Blog Templates.
 *
 * => Action hooked 'astra_entry_content_search'
 * 
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_content_search', 'astra_entry_content_blog_template' );


/**
 * Content Blog Templates
 *
 * => Used in files:
 *
 * /template-parts/content-blog.php
 * /template-parts/search/search-layout.php
 *
 * @since 1.0.0
 */
function astra_entry_content_blog_template() {
	get_template_part( 'template-parts/blog/blog-layout' );
}
