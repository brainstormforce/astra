<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Astra
 * @since 1.0.0
 */

?>

<?php get_header(); ?>

<?php astra_primary_before(); ?>

	<div <?php astra_attr( 'primary', '404' ); ?>>

		<?php astra_primary_content_top(); ?>

		<main <?php astra_attr( 'main', '404' ); ?>>

			<?php astra_entry_before(); ?>

			<section class="error-404 not-found">

				<?php astra_entry_top(); ?>

				<?php astra_entry_content_404_page(); ?>

				<?php astra_entry_bottom(); ?>

			</section><!-- .error-404 -->

			<?php astra_entry_after(); ?>

		</main><!-- #main -->

		<?php astra_primary_content_bottom(); ?>

	</div><!-- #primary -->

<?php astra_primary_after(); ?>

<?php get_footer(); ?>
