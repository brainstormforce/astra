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

<?php

/**
 * 404 Content Template Part
 *
 * => Action hooked 'astra_entry_content_404_page'
 *
 * 'astra_entry_content_404_page_template' function found in "framework/structure/404.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_content_404_page', 'astra_entry_content_404_page_template' );

?>

<?php get_header(); ?>

<?php astra_primary_before(); ?>

	<div id="primary" <?php astra_primary_class(); ?>>

		<?php astra_primary_content_top(); ?>

		<main id="main" class="site-main" role="main">

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
