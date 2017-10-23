<?php
/**
 * Sidebar Widgets.
 *
 * @package Astra
 * @since 1.0.0
 */

/**
 * Sidebars Widgets
 *
 * => Action hooked 'astra_sidebars_content'
 *
 * Action found in /sidebar.php
 *
 * @since 1.0.0
 */
add_action( 'astra_sidebars_content', 'astra_sidebars_widget' );
/**
 * Sidebar Widgets.
 */
function astra_sidebars_widget() {

	$sidebar = apply_filters( 'astra_get_sidebar', 'sidebar-1' );

	if ( is_active_sidebar( $sidebar ) ) :
		dynamic_sidebar( $sidebar );
	endif;
}
