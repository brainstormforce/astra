<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Astra
 * @since 1.0.0
 */

/**
 * Search markup ( Post found )
 *
 * => Action hooked in standard loop for markup
 *
 * 'astra_loop_content' action found in "framework/structure/loops.php"
 * 'astra_search_content_template' function found in "framework/structure/search.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_loop_content', 'astra_search_content_template' );

/**
 * Search markup ( Post not found )
 *
 * => Action hooked in standard loop else for markup
 *
 * 'astra_loop_content_else' action found in "framework/structure/loops"
 * 'astra_search_content_else_template' function found in "framework/structure/search.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_loop_content_else', 'astra_search_content_else_template' );

get_header();
?>

<?php astra_primary_before(); ?>

	<div id="primary" <?php astra_primary_class(); ?>>

		<?php astra_primary_content_top(); ?>

		<?php astra_archive_header(); ?>

		<main id="main" class="site-main" role="main">

			<div class="ast-row">
				
				<?php astra_before_loop(); ?>
		
				<?php astra_loop(); ?>
				
				<?php astra_after_loop(); ?>

			</div>

		</main><!-- #main -->

		<?php astra_pagination(); ?>

		<?php astra_primary_content_bottom(); ?>

	</div><!-- #primary -->

<?php astra_primary_after(); ?>

<?php get_footer(); ?>
