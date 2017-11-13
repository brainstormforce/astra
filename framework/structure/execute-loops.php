<?php
/**
 * Execute All Loops.
 *
 * @package Astra
 * @since 1.0.0
 */

/**
 * Index markup ( Post Found )
 *
 * => Action hooked in standard loop for markup
 *
 * 'astra_loop_content' action found in "framework/structure/loops.php"
 * 'astra_index_content_template' function found in "framework/structure/index.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_loop_content', 'astra_do_loop_content' );

/**
 * Index Template Part
 *
 * => Used in files:
 *
 * /index.php
 *
 * @since 1.0.0
 */
function astra_do_loop_content() {
	
	vl( is_404() );

	if ( is_home() || is_archive() ) {
		
		/**
		 * Include the Post-Format-specific template for the content.
		 * If you want to override this in a child theme, then include a file
		 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
		 */
		get_template_part( 'template-parts/content', astra_get_post_format() );
	}elseif ( is_search() ) {
		
		get_template_part( 'template-parts/search/search-layout' );
	}elseif ( is_singular() ) {
		
		if ( is_page() ) {
			
			get_template_part( 'template-parts/content', 'page' );
		}else{
			
			get_template_part( 'template-parts/content', 'single' );
		}
	}
}


/**
 * Index markup ( Post Not Found )
 *
 * => Action hooked in standard loop else for markup
 *
 * 'astra_loop_content_else' action found in "framework/structure/loops"
 * 'astra_index_content_else_template' function found in "framework/structure/index.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_loop_content_else', 'astra_do_loop_content_else' );

/**
 * Index Template Else Part
 *
 * => Used in files:
 *
 * /index.php
 *
 * @since 1.0.0
 */
function astra_do_loop_content_else() {

	get_template_part( 'template-parts/content', 'none' );
}