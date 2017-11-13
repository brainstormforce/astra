<?php
/**
 * Search Functions.
 *
 * @package Astra
 * @since 1.0.0
 */

/**
 * Content Blog Templates.
 *
 * => Action hooked 'astra_entry_content_search'
 * 
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_content', 'astra_entry_content_search_template' );

/**
 * Content Blog Templates
 *
 * => Used in files:
 *
 * /template-parts/search/search-layout.php
 *
 * @since 1.0.0
 */
function astra_entry_content_search_template() {

	if ( is_search() ) {
		
		get_template_part( 'template-parts/blog/blog-layout' );
	}
}