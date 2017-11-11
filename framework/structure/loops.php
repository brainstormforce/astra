<?php
/**
 * Loops for structure.
 *
 * @package Astra
 * @since 1.0.0
 */

add_action( 'astra_loop', 'astra_global_loop' );

/**
 * Global Standard Loop
 *
 * => Action hooked in 'astra_loop' action
 *
 * 'astra_loop' action is used to global loop
 *	
 * @since 1.0.0
 */
function astra_global_loop() {

	if ( have_posts() ) :

		do_action( 'astra_content_while_before' );

		while ( have_posts() ) :
			the_post();

			do_action( 'astra_loop_content' );

		endwhile;

		do_action( 'astra_content_while_after' );

	else :

		do_action( 'astra_loop_content_else' );

	endif;
}
