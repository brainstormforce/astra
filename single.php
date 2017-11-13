<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astra
 * @since 1.0.0
 */

get_header(); ?>

<?php astra_primary_before(); ?>
		
	<div <?php astra_attr( 'primary', 'single' ); ?> <?php astra_primary_class(); ?>>

		<?php astra_primary_content_top(); ?>

		<main <?php astra_attr( 'main', 'single' ); ?> class="site-main" >

			<?php

			astra_before_loop();

			astra_loop();

			astra_after_loop();

			?>

		</main><!-- #main -->

		<?php astra_primary_content_bottom(); ?>

	</div><!-- #primary -->
	
<?php astra_primary_after(); ?>

<?php get_footer(); ?>
