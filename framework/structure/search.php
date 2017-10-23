<?php
/**
 * Search Functions.
 *
 * @package Astra
 * @since 1.0.0
 */

/**
 * Search Template Part
 *
 * => Used in files:
 *
 * /search.php
 *
 * @since 1.0.0
 */
function astra_search_content_template() {

	get_template_part( 'template-parts/search/search-layout' );
}

/**
 * Search Template Part Else
 *
 * => Used in files:
 *
 * /search.php
 *
 * @since 1.0.0
 */
function astra_search_content_else_template() {

	get_template_part( 'template-parts/content', 'none' );
}