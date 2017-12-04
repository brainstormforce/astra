<?php
/**
 * Search Functions.
 *
 * @package Astra
 * @since 1.0.0
 */

add_action( 'astra_entry_content_404_page', 'astra_entry_content_404_page_template' );

/**
 * 404 markup
 *
 * => Used in files:
 *
 * 404.php
 *
 * @since 1.0.0
 */
function astra_entry_content_404_page_template() {

	get_template_part( 'template-parts/404/404-layout' );
}
