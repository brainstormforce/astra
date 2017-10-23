<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 1.0.0
 */

/**
 * Page Content Main Template
 *
 * => Action hooked in standard loop for markup
 *
 * 'astra_loop_content' action found in "framework/structure/loops.php"
 * 'astra_page_content_template' function found in "framework/structure/page.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_loop_content', 'astra_page_content_template' );

get_header(); ?>

<?php astra_primary_before(); ?>

	<div id="primary" <?php astra_primary_class(); ?>>

		<?php astra_primary_content_top(); ?>

		<main id="main" class="site-main" role="main">

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
