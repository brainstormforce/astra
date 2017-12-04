<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 1.0.0
 */

get_header(); ?>

<?php astra_primary_before(); ?>

	<div <?php astra_attr( 'primary', 'archive' ); ?>>

		<?php astra_primary_content_top(); ?>

		<?php astra_archive_header(); ?>

		<main <?php astra_attr( 'main', 'archive' ); ?>>

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
